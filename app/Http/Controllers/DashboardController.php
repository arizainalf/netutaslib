<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buku;
use App\Models\Siswa;
use App\Models\Kategori;
use App\Models\Kunjungan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use JsonResponder;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth();

            $berkunjungData = Kunjungan::whereBetween('tanggal', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(tanggal) as date'),
                    DB::raw('COUNT(*) as count'),
                ])
                ->pluck('count', 'date');

            $peminjamanData = Peminjaman::where('status', '0')
                ->whereBetween('tanggal_mulai', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(tanggal_mulai) as date'),
                    DB::raw('COUNT(*) as count'),
                ])
                ->pluck('count', 'date');

            $pengembalianData = Peminjaman::where('status', '1')
                ->whereBetween('tanggal_kembali', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(tanggal_kembali) as date'),
                    DB::raw('COUNT(*) as count'),
                ])
                ->pluck('count', 'date');

            $labels = [];
            $berkunjung = [];
            $peminjaman = [];
            $pengembalian = [];

            $dates = $startDate->copy();
            while ($dates <= $endDate) {
                $dateString = $dates->toDateString();
                $labels[] = formatTanggal($dateString, 'd');
                $berkunjung[] = $berkunjungData[$dateString] ?? 0;
                $peminjaman[] = $peminjamanData[$dateString] ?? 0;
                $pengembalian[] = $pengembalianData[$dateString] ?? 0;
                $dates->addDay();
            }

            return $this->successResponse([
                'labels' => $labels,
                'berkunjung' => $berkunjung,
                'peminjaman' => $peminjaman,
                'pengembalian' => $pengembalian,
            ], 'Data Kunjungan, Peminjaman dan Pengembalian ditemukan.');
        }

        $books = Buku::count();
        $category = Kategori::count();
        $members = Siswa::count();
        $pengembalian = Peminjaman::where('status', '1')->count();
        $loans = Peminjaman::where('status', '0')->count();
        $berkunjung = Kunjungan::count();

        return view('pages.dashboard.index', compact('books', 'category', 'members', 'loans', 'pengembalian', 'berkunjung'));
    }
}
