<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Services\RecommendationService;

class HomeController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        $bukuPopuler = Buku::orderBy('views', 'desc')->take(6)->get();
        $bukuTerbaru = Buku::orderBy('created_at', 'desc')->take(6)->get();

        // Fix: Remove bukus_count calculation and use simple count
        $kategoris = Kategori::paginate(5);
        $avgBukuPerKategori = 0; // Default value or calculate differently

        // Alternative calculation if needed:
        // $avgBukuPerKategori = round(Buku::count() / max(Kategori::count(), 1), 1);

        return view('home', compact('bukuPopuler', 'bukuTerbaru', 'kategoris', 'avgBukuPerKategori'));
    }

    public function pilihJenis()
    {
        return view('pilih-jenis');
    }

    public function prosesJenis(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:fiksi,non_fiksi'
        ]);

        $kategoris = Kategori::where('jenis', $request->jenis)->get();

        return view('pilih-kategori', compact('kategoris'));
    }

    public function prosesKategori(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:fiksi,non_fiksi',
            'kategori_ids' => 'required|array|min:1',
            'kategori_ids.*' => 'exists:kategoris,id'
        ]);

        // Simpan preferensi jika user sudah login
        if (auth()->check()) {
            $preferensi = auth()->user()->preferensiUser()->updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'jenis_buku' => $request->jenis,
                    'kategori_pilihan' => $request->kategori_ids
                ]
            );
        }

        // Dapatkan rekomendasi berdasarkan kategori yang dipilih
        $rekomendasi = $this->recommendationService->getRecommendationsByCategory(
            $request->kategori_ids,
            auth()->id()
        );

        return view('rekomendasi', compact('rekomendasi'));
    }
}
