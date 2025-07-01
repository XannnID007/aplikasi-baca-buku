<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\RiwayatBacaan;
use App\Models\Bookmark;
use App\Models\Rating;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategoris');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('penulis', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('kategori')) {
            $query->whereHas('kategoris', function ($q) use ($request) {
                $q->where('kategori_id', $request->kategori);
            });
        }

        $bukus = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('buku.index', compact('bukus'));
    }

    public function show(Buku $buku)
    {
        // Increment views
        $buku->increment('views');

        $buku->load('kategoris', 'ratings.user');

        $userRating = null;
        $userBookmark = null;
        $riwayatBacaan = null;

        if (auth()->check()) {
            $userRating = Rating::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->first();

            $userBookmark = Bookmark::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->exists();

            $riwayatBacaan = RiwayatBacaan::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->first();
        }

        return view('buku.show', compact('buku', 'userRating', 'userBookmark', 'riwayatBacaan'));
    }

    public function baca(Buku $buku, Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk membaca buku.');
        }

        $halaman = $request->get('halaman', 1);

        // Update atau create riwayat bacaan
        RiwayatBacaan::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'buku_id' => $buku->id
            ],
            [
                'halaman_terakhir' => $halaman,
                'terakhir_dibaca' => now()
            ]
        );

        return view('buku.baca', compact('buku', 'halaman'));
    }

    public function toggleBookmark(Buku $buku)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $bookmark = Bookmark::where('user_id', auth()->id())
            ->where('buku_id', $buku->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['bookmarked' => false, 'message' => 'Bookmark dihapus']);
        } else {
            Bookmark::create([
                'user_id' => auth()->id(),
                'buku_id' => $buku->id
            ]);
            return response()->json(['bookmarked' => true, 'message' => 'Bookmark ditambahkan']);
        }
    }

    public function rating(Request $request, Buku $buku)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'buku_id' => $buku->id
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review
            ]
        );

        // Update rating rata-rata buku
        $buku->updateRating();

        return response()->json(['message' => 'Rating berhasil disimpan']);
    }
}
