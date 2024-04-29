<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $loans = Loan::with('user', 'book', 'member')->get();
            if ($request->mode == "datatable") {
                return DataTables::of($loans)
                    ->addColumn('action', function ($loan) {
                        $approveButton = $loan->status == '0' ? '<button class="btn btn-sm btn-info d-inline-flex  align-items-baseline " onclick="confirmApprove(`/loan/approve/' . $loan->id . '`, `loan-table`)"><i class="fa-solid fa-book-bookmark mr-1"></i>Konfirmasi</button>' : '<span class="badge badge-pill badge-success">Dikembalikan</span>';
                        return $approveButton;
                    })
                    ->addColumn('user', function ($loan) {
                        return $loan->user->nama;
                    })
                    ->addColumn('member', function ($loan) {
                        return $loan->member->nama;
                    })
                    ->addColumn('book', function ($loan) {
                        return $loan->book->judul;
                    })
                    ->addColumn('tanggal_mulai', function ($loan) {
                        return formatTanggal($loan->tanggal_mulai);
                    })
                    ->addColumn('tanggal_selesai', function ($loan) {
                        return ($loan->tanggal_selesai == null) ? formatTanggal($loan->tanggal_selesai) : formatTanggal($loan->tanggal_selesai);
                    })
                    ->addColumn('status', function ($loan) {
                        if ($loan->status == '0') {
                            return '<span class="badge badge-pill badge-primary">Dipinjam</span>';
                        } else {
                            return '<span class="badge badge-pill badge-success">Dikembalikan</span>';
                        }
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action', 'user', 'member', 'book', 'tanggal_mulai', 'tanggal_selesai', 'status'])
                    ->make(true);
            }

            return $this->successResponse($loans, 'Data Buku ditemukan.');
        }

        return view('pages.loan.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_selesai' => 'required',
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $tanggal = now()->format('Y-m-d');

        $loan = Loan::create([
            'kode' => $this->generateKode($tanggal),
            'user_id' => auth()->user()->id,
            'book_id' => $request->book_id,
            'member_id' => $request->member_id,
            'tanggal_mulai' => $tanggal,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return $this->successResponse($loan, 'Data Buku Disimpan!', 201);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_selesai' => 'required',
            'member_id' => 'required|exists:member,id',
            'book_id' => 'required|exists:book,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $book = Book::find($id);

        if (!$book) {
            return $this->errorResponse(null, 'Data Buku Tidak Ada!');
        }

        $updateLoan = [
            'book_id' => $request->book_id,
            'member_id' => $request->member_id,
            'tanggal_mulai' => $tanggal,
            'tanggal_selesai' => $request->tanggal_selesai,
        ];

        $book->update($updateBook);

        return $this->successResponse($book, 'Data Buku Diubah!');
    }

    private function generateKode($tanggal)
    {
        $cekData = Loan::whereDate('tanggal_mulai', $tanggal)->count();
        $datake = $cekData + 1;
        $tanggalFormatted = date('Ymd', strtotime($tanggal));
        $datakeFormatted = str_pad($datake, 3, '0', STR_PAD_LEFT);
        $kode = "PIB-$tanggalFormatted-$datakeFormatted";

        return $kode;
    }

    public function approve(string $id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return $this->errorResponse(null, 'Data Peminjaman Tidak Ada!');
        }
        $loan->update([
            'status' => '1',
        ]);
        return $this->successResponse($loan, 'Buku Yang Dipinjam sudah Dikembalikan!');
    }
    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new BarangExport(), 'Barang.xlsx');
        } elseif ($id == 'pdf') {
            $loans = Loan::with('book', 'member', 'user')->get();
            $pdf = PDF::loadView('pages.loan.pdf', compact('loans'));

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
            $barang = Barang::find($id);

            if (!$barang) {
                return $this->errorResponse(null, 'Data Barang tidak ditemukan.', 404);
            }

            return $this->successResponse($barang, 'Data Barang ditemukan.');
        }
    }
}
