<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kategoris = Kategori::all();
            if ($request->mode == "datatable") {
                return DataTables::of($kategoris)
                    ->addColumn('action', function ($kategori) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/admin/kategori/' . $kategori->id . '`, [`id`, `nama`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/kategori/' . $kategori->id . '`, `category-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return $this->successResponse($kategoris, 'Data kategori ditemukan.');
        }

        return view('pages.kategori.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $kategori = Kategori::create($request->only('nama'));

        return $this->successResponse($kategori, 'Data Kategori Disimpan!', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return $this->errorResponse(null, 'Data Kategori Tidak Ada!');
        }

        $kategori->update($request->only('nama'));

        return $this->successResponse($kategori, 'Data Kategori Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return $this->errorResponse(null, 'Data Kategori Tidak Ada!');
        }

        $kategori->delete();

        return $this->successResponse(null, 'Data Kategori Dihapus!');
    }
    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new CategoryExport(), 'Kategori.xlsx');
        } elseif ($id == 'pdf') {
            $kategoris = Kategori::all();
            $pdf = PDF::loadView('pages.kategori.pdf', compact('categories'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('a4', 'landscape');

            $namaFile = 'Kategori.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        } else {
            $barang = Kategori::find($id);

            if (!$barang) {
                return $this->errorResponse(null, 'Data Kategori tidak ditemukan.', 404);
            }

            return $this->successResponse($barang, 'Data Kategori ditemukan.');
        }
    }
}
