<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Buku;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PeminjamanTahunan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PeminjamanTahunanController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $peminjamans = PeminjamanTahunan::with('user', 'mapel', 'siswa')->get();
            if ($request->mode == "datatable") {
                return DataTables::of($peminjamans)
                    ->addColumn('action', function ($peminjaman) {
                        $approveButton = $peminjaman->status == '0' ? '<button class="btn btn-sm btn-info d-inline-flex  align-items-baseline " onclick="confirmApprove(`/admin/peminjamanmapel/approve/' . $peminjaman->id . '`, `loan-table`)"><i class="fa-solid fa-book-bookmark mr-1"></i>Konfirmasi</button>' : '<span class="badge badge-success">Dikembalikan</span>';
                        return $approveButton;
                    })
                    ->addColumn('user', function ($peminjaman) {
                        return $peminjaman->user->nama;
                    })
                    ->addColumn('siswa', function ($peminjaman) {
                        return $peminjaman->siswa->nama;
                    })
                    ->addColumn('mapel', function ($peminjaman) {
                        return $peminjaman->mapel->nama;
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
                    ->rawColumns(['action', 'user', 'siswa', 'mapel', 'tanggal_mulai', 'tanggal_selesai', 'status'])
                    ->make(true);
            }

            return $this->successResponse($peminjamans, 'Data Buku ditemukan.');
        }
        return view('pages.peminjaman_tahunan.index');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_selesai' => 'required',
            'id_siswa' => 'required|exists:siswas,id',
            'id_mapel' => 'required|exists:mapels,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $tanggal = now()->format('Y-m-d');

        $peminjaman = PeminjamanTahunan::create([
            'kode' => $this->generateKode($tanggal),
            'user_id' => auth()->user()->id,
            'id_mapel' => $request->id_mapel,
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
            'id_mapel' => 'required|exists:mapels,id',
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
            'id_mapel' => $request->id_mapel,
            'id_siswa' => $request->id_siswa,
            'tanggal_mulai' => $tanggal,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tanggal_kembali' => $tanggalKembali,
        ];

        $buku->update($updateBook);

        return $this->successResponse($buku, 'Data Buku Diubah!');
    }

    private function generateKode($tanggal, $idKelas, $currentCount)
    {
        $tanggalFormatted = date('Ymd', strtotime($tanggal));
        $currentCountFormatted = str_pad($currentCount, 3, '0', STR_PAD_LEFT);
        $kode = "{$idKelas}-{$tanggalFormatted}-{$currentCountFormatted}";

        return $kode;
    }
    public function approve(string $id)
    {
        $tanggalKembali = date('Y-m-d');
        $peminjaman = PeminjamanTahunan::find($id);
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
            $peminjamans = PeminjamanTahunan::with('buku', 'siswa', 'user')->get();
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
            $barang = PeminjamanTahunan::find($id);

            if (!$barang) {
                return $this->errorResponse(null, 'Data Peminjaman tidak ditemukan.', 404);
            }

            return $this->successResponse($barang, 'Data Peminjaman ditemukan.');
        }
    }
    public function massInsert(Request $request)
    {
        // Validasi input data
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id',
            'id_mapel' => 'required|exists:mapels,id',
            'tanggal_selesai' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $tanggal = now()->format('Y-m-d');
        $userId = auth()->user()->id;
        $idKelas = $request->id_kelas;
        $idMapel = $request->id_mapel;
        $tanggalSelesai = $request->tanggal_selesai;

        // Ambil semua siswa berdasarkan id_kelas
        $siswaList = Siswa::where('id_kelas', $idKelas)->get();

        // Persiapkan data untuk mass insert
        $peminjamanData = [];
        $currentCount = PeminjamanTahunan::where('id_kelas', $idKelas)
            ->whereDate('tanggal_mulai', $tanggal)
            ->count();

        foreach ($siswaList as $siswa) {
            $currentCount++;
            $kode = $this->generateKode($tanggal, $idKelas, $currentCount);
            $peminjamanData[] = [
                'kode' => $kode,
                'user_id' => $userId,
                'id_mapel' => $idMapel,
                'id_siswa' => $siswa->id,
                'id_kelas' => $idKelas, // tambahkan id_kelas
                'tanggal_mulai' => $tanggal,
                'tanggal_selesai' => $tanggalSelesai,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Lakukan mass insert
        PeminjamanTahunan::insert($peminjamanData);

        return $this->successResponse(null, 'Data Peminjaman berhasil disimpan secara massal berdasarkan kelas!', 201);
    }
}
