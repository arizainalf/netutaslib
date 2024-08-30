<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{

    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->tanggal_selesai;
        if ($request->ajax()) {
            if ($request->mode == "datatable") {
                $kunjungans = Kunjungan::with('member')
                    ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                    ->latest()
                    ->get();
                return DataTables::of($kunjungans)
                    ->addColumn('tanggal', function ($kunjungan) {
                        return formatTanggal($kunjungan->tanggal);
                    })
                    ->addColumn('nisn', function ($kunjungan) {
                        return $kunjungan->member->nisn;
                    })
                    ->addColumn('nipd', function ($kunjungan) {
                        return $kunjungan->member->nipd;
                    })
                    ->addColumn('member', function ($kunjungan) {
                        return $kunjungan->member->nama;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['tanggal', 'member'])
                    ->make(true);
            }

            return $this->successResponse($kunjungans, 'Data Kunjungan ditemukan.');
        }

        if ($request->mode == "pdf") {
            $kunjungans = Kunjungan::with('member')
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                ->latest()
                ->get();
            $pdf = PDF::loadView('pages.kunjungan.pdf', compact('visits', 'tanggalMulai', 'tanggalSelesai'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('legal', 'potrait');

            $namaFile = 'Data_Kunjungan_' . $tanggalMulai . '_' . $tanggalSelesai . '.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        }

        return view('pages.kunjungan.index');
    }
}
