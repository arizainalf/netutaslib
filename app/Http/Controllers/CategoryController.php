<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\JsonResponder;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::all();
            if ($request->mode == "datatable") {
                return DataTables::of($categories)
                    ->addColumn('action', function ($category) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/category/' . $category->id . '`, [`id`, `nama`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/category/' . $category->id . '`, `category-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return $this->successResponse($categories, 'Data Kategori ditemukan.');
        }

        return view('pages.category.index');
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

        $category = Category::create($request->only('nama'));

        return $this->successResponse($category, 'Data Kategori Disimpan!', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse(null, 'Data Kategori Tidak Ada!');
        }

        return $this->successResponse($category, 'Data Kategori Ditemukan!');
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
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse(null, 'Data Kategori Tidak Ada!');
        }

        $category->update($request->only('nama'));

        return $this->successResponse($category, 'Data Kategori Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse(null, 'Data Kategori Tidak Ada!');
        }

        $category->delete();

        return $this->successResponse(null, 'Data Kategori Dihapus!');
    }
}
