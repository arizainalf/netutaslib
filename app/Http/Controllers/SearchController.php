<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kunjungan;
use App\Traits\JsonResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    use JsonResponder;
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'required|exists:siswas,nipd',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data Siswa Tidak Ditemukan.', 422);
        }

        $keyword = $request->keyword;

        $siswa = Siswa::where('nipd', $keyword)->orWhere('nipd', $keyword)->first();

        return $this->successResponse($siswa, 'Data Siswa Ditemukan!');
    }
    
    public function saveAttend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required|exists:siswas,id',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Kepentingan Kunjungan Wajib Diisi!.', 422);
        }

        $cekVisit = Kunjungan::where('id_siswa', $request->id_siswa)->where('tanggal', date('Y-m-d'))->first();
        if ($cekVisit) {
            return $this->errorResponse(null, 'Maaf Anda Sudah Berkunjung Hari Ini!');
        }
        $visit = Kunjungan::create([
            'id_siswa' => $request->id_siswa,
            'deskripsi' => $request->deskripsi,
            'tanggal' => now(),
        ]);
        return $this->successResponse($visit, 'Data Kunjungan Disimpan!', 201);
    }
}