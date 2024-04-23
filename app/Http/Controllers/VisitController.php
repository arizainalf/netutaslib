<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Traits\JsonResponder;
use DataTables;
use Illuminate\Http\Request;

class VisitController extends Controller
{

    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tanggal = $request->tanggal;
            $visits = Visit::with('member')->whereDate('created_at', $tanggal)->latest()->get();
            if ($request->mode == "datatable") {
                return DataTables::of($visits)
                    ->addColumn('tanggal', function ($visit) {
                        return formatTanggal($visit->created_at);
                    })
                    ->addColumn('nisn', function ($visit) {
                        return $visit->member->nisn;
                    })
                    ->addColumn('nipd', function ($visit) {
                        return $visit->member->nipd;
                    })
                    ->addColumn('member', function ($visit) {
                        return $visit->member->nama;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['tanggal', 'member'])
                    ->make(true);
            }

            return $this->successResponse($visits, 'Data Kunjungan ditemukan.');
        }

        return view('pages.visit.index');
    }
}