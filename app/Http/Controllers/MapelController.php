<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Mapel;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class MapelController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $mapels = Mapel::all();
            if ($request->mode == "datatable") {
                return DataTables::of($mapels)
                    ->addColumn('action', function ($mapel) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/admin/mapel/' . $mapel->id . '`, [`id`, `nama`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/mapel/' . $mapel->id . '`, `mapel-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return $this->successResponse($mapels, 'Data mapel ditemukan.');
        }

        return view('pages.mapel.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $mapel = Mapel::create($request->only('nama'));

        return $this->successResponse($mapel, 'Data Mapel Disimpan!', 201);
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
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return $this->errorResponse(null, 'Data Mapel Tidak Ada!');
        }

        $mapel->update($request->only('nama'));

        return $this->successResponse($mapel, 'Data Mapel Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return $this->errorResponse(null, 'Data Mapel Tidak Ada!');
        }

        $mapel->delete();

        return $this->successResponse(null, 'Data Mapel Dihapus!');
    }
    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new CategoryExport(), 'mapel.xlsx');
        } elseif ($id == 'pdf') {
            $mapels = Mapel::all();
            $pdf = PDF::loadView('pages.pdf', compact('mapels'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('a4', 'landscape');

            $namaFile = 'mapel.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        } else {
            $barang = Mapel::find($id);

            if (!$barang) {
                return $this->errorResponse(null, 'Data Mapel tidak ditemukan.', 404);
            }

            return $this->successResponse($barang, 'Data Mapel ditemukan.');
        }
    }
}