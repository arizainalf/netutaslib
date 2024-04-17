<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Traits\JsonResponder;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/member/' . $member->id . '`, [`id`, `nama`,`nipd`,`nisn`,`jenis_kelamin`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/member/' . $member->id . '`, `member-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return $this->successResponse($m, 'Data Member ditemukan.');
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return $this->errorResponse(null, 'Data Member Tidak Ada!');
        }

        return $this->successResponse($member, 'Data Member Ditemukan!');
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
}