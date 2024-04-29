<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Http\Request;

class VisitController extends Controller
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
                $visits = Visit::with('member')
                    ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                    ->latest()
                    ->get();
                return DataTables::of($visits)
                    ->addColumn('tanggal', function ($visit) {
                        return formatTanggal($visit->tanggal);
                    })
                    ->addColumn('nisn', function ($visit) {
                        return $visit->member->nisn;
                    })
                    ->addColumn('nipd', function ($visit) {
                        return $visit->member->nipd;
                    })
                    ->addColumn('member', function ($visit) {
                        return $visit->member->nama;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['tanggal', 'member'])
                    ->make(true);
            }

            return $this->successResponse($visits, 'Data Kunjungan ditemukan.');
        }

        if ($request->mode == "pdf") {
            $visits = Visit::with('member')
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                ->latest()
                ->get();
            $pdf = PDF::loadView('pages.visit.pdf', compact('visits', 'tanggalMulai', 'tanggalSelesai'));

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

        return view('pages.visit.index');
    }
}
