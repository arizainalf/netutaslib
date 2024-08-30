<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kelases = Kelas::all();
            if ($request->mode == "datatable") {
                return DataTables::of($kelases)
                    ->addColumn('action', function ($kelas) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/admin/kelas/' . $kelas->id . '`, [`id`, `nama`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/kelas/' . $kelas->id . '`, `kelas-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return $this->successResponse($kelases, 'Data Kelas ditemukan.');
        }

        return view('pages.kelas.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $kelas = Kelas::create($request->only('nama'));

        return $this->successResponse($kelas, 'Data Kelas Disimpan!', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return $this->errorResponse(null, 'Data Kelas Tidak Ada!');
        }

        $kelas->update($request->only('nama'));

        return $this->successResponse($kelas, 'Data Kelas Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::find($id);

        if (!$kelas) {
            return $this->errorResponse(null, 'Data Kelas Tidak Ada!');
        }

        $kelas->delete();

        return $this->successResponse(null, 'Data Kelas Dihapus!');
    }
    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new CategoryExport(), 'Kelas.xlsx');
        } elseif ($id == 'pdf') {
            $kelases = Kelas::all();
            $pdf = PDF::loadView('pages.kelas.pdf', compact('kelases'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('a4', 'landscape');

            $namaFile = 'Kelas.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        } else {
            $kelas = Kelas::find($id);

            if (!$kelas) {
                return $this->errorResponse(null, 'Data Kelas tidak ditemukan.', 404);
            }

            return $this->successResponse($kelas, 'Data Kelas ditemukan.');
        }
    }
}