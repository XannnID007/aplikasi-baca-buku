<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\User;
use App\Models\Rating;
use App\Models\RiwayatBacaan;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function laporanBuku()
    {
        $totalBuku = Buku::count();
        $bukuFiksi = Buku::where('jenis', 'fiksi')->count();
        $bukuNonFiksi = Buku::where('jenis', 'non_fiksi')->count();
        $bukuPopuler = Buku::orderBy('views', 'desc')->take(10)->get();
        $bukuTerbaru = Buku::orderBy('created_at', 'desc')->take(10)->get();

        // Data untuk chart
        $chartData = [
            'labels' => ['Fiksi', 'Non-Fiksi'],
            'data' => [$bukuFiksi, $bukuNonFiksi]
        ];

        return view('admin.laporan.buku', compact(
            'totalBuku',
            'bukuFiksi',
            'bukuNonFiksi',
            'bukuPopuler',
            'bukuTerbaru',
            'chartData'
        ));
    }

    public function laporanUser()
    {
        $totalUser = User::where('role', 'user')->count();
        $userAktif = User::where('role', 'user')
            ->whereHas('riwayatBacaans', function ($query) {
                $query->where('terakhir_dibaca', '>=', Carbon::now()->subDays(30));
            })->count();

        $userTerbaru = User::where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $userTerbaik = User::where('role', 'user')
            ->withCount('riwayatBacaans')
            ->orderBy('riwayat_bacaans_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.laporan.user', compact(
            'totalUser',
            'userAktif',
            'userTerbaru',
            'userTerbaik'
        ));
    }

    public function laporanRating()
    {
        $totalRating = Rating::count();
        $ratingRataRata = Rating::avg('rating');
        $ratingTerbaru = Rating::with(['user', 'buku'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        // Distribution of ratings
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingDistribution[$i] = Rating::where('rating', $i)->count();
        }

        return view('admin.laporan.rating', compact(
            'totalRating',
            'ratingRataRata',
            'ratingTerbaru',
            'ratingDistribution'
        ));
    }

    public function laporanPopuler()
    {
        $bukuPopuler = Buku::orderBy('views', 'desc')->take(20)->get();
        $bukuTerrating = Buku::orderBy('rating_rata_rata', 'desc')
            ->where('total_ratings', '>=', 5)
            ->take(20)
            ->get();

        $trendBulanan = Buku::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->whereYear('created_at', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.laporan.populer', compact(
            'bukuPopuler',
            'bukuTerrating',
            'trendBulanan'
        ));
    }
}
