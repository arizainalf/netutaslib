<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $peminjamans = Peminjaman::with('user', 'buku', 'siswa')->get();
            if ($request->mode == "datatable") {
                return DataTables::of($peminjamans)
                    ->addColumn('action', function ($peminjaman) {
                        $approveButton = $peminjaman->status == '0' ? '<button class="btn btn-sm btn-info d-inline-flex  align-items-baseline " onclick="confirmApprove(`/admin/loan/approve/' . $peminjaman->id . '`, `loan-table`)"><i class="fa-solid fa-book-bookmark mr-1"></i>Konfirmasi</button>' : '<span class="badge badge-success">Dikembalikan</span>';
                        return $approveButton;
                    })
                    ->addColumn('user', function ($peminjaman) {
                        return $peminjaman->user->nama;
                    })
                    ->addColumn('siswa', function ($peminjaman) {
                        return $peminjaman->siswa->nama;
                    })
                    ->addColumn('buku', function ($peminjaman) {
                        return $peminjaman->buku->judul;
                    })
                    ->addColumn('tanggal_mulai', function ($peminjaman) {
                        return formatTanggal($peminjaman->tanggal_mulai);
                    })
                    ->addColumn('tanggal_selesai', function ($peminjaman) {
                        return ($peminjaman->tanggal_selesai == null) ? formatTanggal($peminjaman->tanggal_selesai) : formatTanggal($peminjaman->tanggal_selesai);
                    })
                    ->addColumn('status', function ($peminjaman) {
                        if ($peminjaman->status == '0') {
                            return '<span class="badge badge-primary">Dipinjam</span>';
                        } else {
                            return '<span class="badge badge-success">Dikembalikan</span>';
                        }
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action', 'user', 'siswa', 'buku', 'tanggal_mulai', 'tanggal_selesai', 'status'])
                    ->make(true);
            }

            return $this->successResponse($peminjamans, 'Data Buku ditemukan.');
        }
        return view('pages.peminjaman.index');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_selesai' => 'required',
            'id_siswa' => 'required|exists:siswas,id',
            'id_buku' => 'required|exists:bukus,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $tanggal = now()->format('Y-m-d');

        $peminjaman = Peminjaman::create([
            'kode' => $this->generateKode($tanggal),
            'user_id' => auth()->user()->id,
            'id_buku' => $request->id_buku,
            'id_siswa' => $request->id_siswa,
            'tanggal_mulai' => $tanggal,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return $this->successResponse($peminjaman, 'Data Buku Disimpan!', 201);
    }
    /**
     * Update the specified resource in storage.
     **/
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_selesai' => 'required',
            'id_siswa' => 'required|exists:siswas,id',
            'id_buku' => 'required|exists:bukus,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $buku = Buku::find($id);

        if (!$buku) {
            return $this->errorResponse(null, 'Data Buku Tidak Ada!');
        }

        $tanggalKembali = date('Y-m-d');

        $updateLoan = [
            'id_buku' => $request->id_buku,
            'id_siswa' => $request->id_siswa,
            'tanggal_mulai' => $tanggal,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tanggal_kembali' => $tanggalKembali,
        ];

        $buku->update($updateBook);

        return $this->successResponse($buku, 'Data Buku Diubah!');
    }

    private function generateKode($tanggal)
    {
        $cekData = Peminjaman::whereDate('tanggal_mulai', $tanggal)->count();
        $datake = $cekData + 1;
        $tanggalFormatted = date('Ymd', strtotime($tanggal));
        $datakeFormatted = str_pad($datake, 3, '0', STR_PAD_LEFT);
        $kode = "PIB-$tanggalFormatted-$datakeFormatted";

        return $kode;
    }
    public function approve(string $id)
    {
        $tanggalKembali = date('Y-m-d');
        $peminjaman = Peminjaman::find($id);
        if (!$peminjaman) {
            return $this->errorResponse(null, 'Data Peminjaman Tidak Ada!');
        }
        $peminjaman->update([
            'status' => '1',
            'tanggal_kembali' => $tanggalKembali,
        ]);
        return $this->successResponse($peminjaman, 'Buku Yang Dipinjam sudah Dikembalikan!');
    }
    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new LoanExport(), 'Peminjaman.xlsx');
        } elseif ($id == 'pdf') {
            $peminjamans = Peminjaman::with('buku', 'siswa', 'user')->get();
            $pdf = PDF::loadView('pages.peminjaman.pdf', compact('loans'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('a4', 'landscape');

            $namaFile = 'Peminjaman.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        } else {
            $barang = Peminjaman::find($id);

            if (!$barang) {
                return $this->errorResponse(null, 'Data Peminjaman tidak ditemukan.', 404);
            }

            return $this->successResponse($barang, 'Data Peminjaman ditemukan.');
        }
    }
}