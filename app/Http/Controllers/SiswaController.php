<?php

namespace App\Http\Controllers;

use App\Exports\ExportMembers;
use App\Http\Controllers\Controller;
use App\Imports\ImportMembers;
use App\Models\Siswa;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $siswas = Siswa::with('kelas')->get();
            if ($request->mode == "datatable") {
                return DataTables::of($siswas)
                    ->addColumn('action', function ($siswa) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex align-items-baseline mr-1" onclick="getModal(`createModal`, `/admin/siswa/' . $siswa->id . '`, [`id`,`id_kelas`, `nama`,`nipd`,`nisn`,`jenis_kelamin`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex align-items-baseline" onclick="confirmDelete(`/admin/siswa/' . $siswa->id . '`, `siswa-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addColumn('kelas', function ($siswa) {
                        return optional($siswa->kelas)->nama;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action', 'kelas'])
                    ->make(true);
            }
        
            return $this->successResponse($siswas, 'Data Member ditemukan.');
        }

        return view('pages.siswa.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id',
            'nisn' => 'required|numeric|unique:siswas,nisn',
            'nipd' => 'required|numeric|unique:siswas,nipd',
            'nama' => 'required|min:4',
            'jenis_kelamin' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $siswa = Siswa::create($request->only('id_kelas','nama', 'nisn', 'nipd', 'jenis_kelamin'));

        return $this->successResponse($siswa, 'Data Member Disimpan!', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required|exists:kelas,id',
            'nisn' => 'required|numeric|unique:siswas,nisn,' . $id,
            'nipd' => 'required|numeric|unique:siswas,nipd,' . $id,
            'nama' => 'required|min:4',
            'jenis_kelamin' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return $this->errorResponse(null, 'Data Member Tidak Ada!');
        }

        $siswa->update($request->only('id_kelas','nama', 'nisn', 'nipd', 'jenis_kelamin'));

        return $this->successResponse($siswa, 'Data Member Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return $this->errorResponse(null, 'Data Member Tidak Ada!');
        }
        $siswa->delete();

        return $this->successResponse(null, 'Data Member Dihapus!');
    }
    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new ExportMembers(), 'Siswa.xlsx');
        } elseif ($id == 'pdf') {
            $siswas = Siswa::all();
            $pdf = PDF::loadView('pages.siswa.pdf', compact('members'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('a4', 'landscape');

            $namaFile = 'Siswa.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        } else {
            $siswa = Siswa::find($id);

            if (!$siswa) {
                return $this->errorResponse(null, 'Data Member tidak ditemukan.', 404);
            }

            return $this->successResponse($siswa, 'Data Member ditemukan.');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls',
        ]);
        Excel::import(new ImportMembers(), request()->file('excel'));

        return $this->successResponse(null, 'Data Excel Berhasil Di Import.');
    }
}