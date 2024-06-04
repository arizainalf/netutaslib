<?php

namespace App\Http\Controllers;

use App\Exports\ExportMembers;
use App\Http\Controllers\Controller;
use App\Imports\ImportMembers;
use App\Models\Member;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $members = Member::all();
            if ($request->mode == "datatable") {
                return DataTables::of($members)
                    ->addColumn('action', function ($member) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/admin/member/' . $member->id . '`, [`id`, `nama`,`nipd`,`nisn`,`jenis_kelamin`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/member/' . $member->id . '`, `member-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return $this->successResponse($members, 'Data Member ditemukan.');
        }

        return view('pages.member.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|numeric|unique:members,nisn',
            'nipd' => 'required|numeric|unique:members,nipd',
            'nama' => 'required|min:4',
            'jenis_kelamin' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $member = Member::create($request->only('nama', 'nisn', 'nipd', 'jenis_kelamin'));

        return $this->successResponse($member, 'Data Member Disimpan!', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|numeric|unique:members,nisn,' . $id,
            'nipd' => 'required|numeric|unique:members,nipd,' . $id,
            'nama' => 'required|min:4',
            'jenis_kelamin' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $member = Member::find($id);

        if (!$member) {
            return $this->errorResponse(null, 'Data Member Tidak Ada!');
        }

        $member->update($request->only('nama', 'nisn', 'nipd', 'jenis_kelamin'));

        return $this->successResponse($member, 'Data Member Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return $this->errorResponse(null, 'Data Member Tidak Ada!');
        }
        $member->delete();

        return $this->successResponse(null, 'Data Member Dihapus!');
    }
    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new ExportMembers(), 'Siswa.xlsx');
        } elseif ($id == 'pdf') {
            $members = Member::all();
            $pdf = PDF::loadView('pages.member.pdf', compact('members'));

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
            $member = Member::find($id);

            if (!$member) {
                return $this->errorResponse(null, 'Data Member tidak ditemukan.', 404);
            }

            return $this->successResponse($member, 'Data Member ditemukan.');
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