<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BukuController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $books = Buku::with('kategori')->get();
            if ($request->mode == "datatable") {
                return DataTables::of($books)
                    ->addColumn('action', function ($book) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/admin/book/' . $book->id . '`, [`id`, `category_id`,`judul`,`penulis`,`penerbit`,`tahun`,`stok`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/book/' . $book->id . '`, `book-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addColumn('image', function ($book) {
                        return '<img src="/storage/img/book/' . $book->image . '" width="150px" alt="">';
                    })
                    ->addColumn('category', function ($book) {
                        return $book->kategori->nama;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action', 'image', 'category'])
                    ->make(true);
            }

            return $this->successResponse($books, 'Data Buku ditemukan.');
        }

        return view('pages.buku.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg|max:5120',
            'stok' => 'required|numeric',
            'id_kategori' => 'required|exists:kategoris,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $image = $request->file('image')->hashName();
        $request->file('image')->storeAs('public/img/book', $image);

        $book = Buku::create([
            'judul' => $request->judul,
            'id_kategori' => $request->id_kategori,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'stok' => $request->stok,
            'image' => $image,
        ]);

        return $this->successResponse($book, 'Data Buku Disimpan!', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|min:4',
            'penulis' => 'required|min:4',
            'penerbit' => 'required|min:4',
            'tahun' => 'required|digits:4',
            'image' => 'image|mimes:png,jpg,jpeg|max:5120',
            'stok' => 'required|numeric|min:1',
            'id_kategori' => 'required|exists:kategoris,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $book = Buku::find($id);

        if (!$book) {
            return $this->errorResponse(null, 'Data Buku Tidak Ada!');
        }

        $updateBook = [
            'judul' => $request->judul,
            'id_kategori' => $request->id_kategori,
            'penulis' => $request->penulis,
            'penerbit' => $request->penerbit,
            'tahun' => $request->tahun,
            'stok' => $request->stok,
        ];

        if ($request->hasFile('image')) {
            if (Storage::exists('public/img/book/' . $book->image)) {
                Storage::delete('public/img/book/' . $book->image);
            }
            $image = $request->file('image')->hashName();
            $request->file('image')->storeAs('public/img/book', $image);
            $updateBook['image'] = $image;
        }
        $book->update($updateBook);

        return $this->successResponse($book, 'Data Buku Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Buku::find($id);

        if (!$book) {
            return $this->errorResponse(null, 'Data Buku Tidak Ada!');
        }

        if (Storage::exists('public/img/book/' . $book->image)) {
            Storage::delete('public/img/book/' . $book->image);
        }

        $book->delete();

        return $this->successResponse(null, 'Data Buku Dihapus!');
    }

    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new BookExport(), 'Buku.xlsx');
        } elseif ($id == 'pdf') {
            $books = Buku::with('category')->get();
            $pdf = PDF::loadView('pages.buku.pdf', compact('books'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('a4', 'landscape');

            $namaFile = 'Book.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        } else {
            $book = Buku::find($id);

            if (!$book) {
                return $this->errorResponse(null, 'Data Buku tidak ditemukan.', 404);
            }

            return $this->successResponse($book, 'Data Buku ditemukan.');
        }
    }
}