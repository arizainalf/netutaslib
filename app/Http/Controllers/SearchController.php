<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visit;
use App\Traits\JsonResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    use JsonResponder;
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'required|exists:members,nisn',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data Member Tidak Ditemukan.', 422);
        }

        $keyword = $request->keyword;

        $member = Member::where('nisn', $keyword)->orWhere('nipd', $keyword)->first();

        return $this->successResponse($member, 'Data Member Ditemukan!');
    }
    public function saveAttend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Kepentingan Kunjungan Wajib Diisi!.', 422);
        }

        $cekVisit = Visit::where('member_id', $request->member_id)->whereDate('created_at', date('Y-m-d'))->first();
        if ($cekVisit) {
            return $this->errorResponse(null, 'Maaf Anda Sudah Berkunjung Hari Ini!');
        }
        $visit = Visit::create($request->only('member_id', 'deskripsi'));
        return $this->successResponse($visit, 'Data Kunjungan Disimpan!', 201);
    }
}