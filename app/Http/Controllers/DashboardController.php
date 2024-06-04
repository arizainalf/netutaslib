<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Visit;
use App\Models\Member;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;

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

            $berkunjungData = Visit::whereBetween('tanggal', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(tanggal) as date'),
                    DB::raw('COUNT(*) as count'),
                ])
                ->pluck('count', 'date');

            $peminjamanData = Loan::where('status', '0')
                ->whereBetween('tanggal_mulai', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(tanggal_mulai) as date'),
                    DB::raw('COUNT(*) as count'),
                ])
                ->pluck('count', 'date');

            $pengembalianData = Loan::where('status', '1')
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

        $books = Book::count();
        $category = Category::count();
        $members = Member::count();
        $pengembalian = Loan::where('status', '1')->count();
        $loans = Loan::where('status', '0')->count();
        $berkunjung = Visit::count();

        return view('pages.dashboard.index', compact('books', 'category', 'members', 'loans', 'pengembalian', 'berkunjung'));
    }
}