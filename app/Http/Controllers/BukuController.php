<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\RiwayatBacaan;
use App\Models\Bookmark;
use App\Models\Rating;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        if ($request->has('rating') && is_numeric($request->rating)) {
            $query->where('rating_rata_rata', '>=', $request->rating);
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating_rata_rata', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('judul', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('judul', 'desc');
                break;
            case 'year_desc':
                $query->orderBy('tahun_terbit', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('tahun_terbit', 'asc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $bukus = $query->paginate(12);

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
                ->whereNull('halaman') // General bookmark, not page-specific
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

        // Validate file exists
        if (!Storage::exists('public/' . $buku->file_path)) {
            return redirect()->route('buku.show', $buku)
                ->with('error', 'File buku tidak ditemukan. Silakan hubungi administrator.');
        }

        // Validate file is PDF
        $mimeType = Storage::mimeType('public/' . $buku->file_path);
        if ($mimeType !== 'application/pdf') {
            return redirect()->route('buku.show', $buku)
                ->with('error', 'File buku harus dalam format PDF.');
        }

        $halaman = $request->get('halaman', 1);

        // Get or create reading history
        $riwayatBacaan = RiwayatBacaan::where('user_id', auth()->id())
            ->where('buku_id', $buku->id)
            ->first();

        if ($riwayatBacaan) {
            // If user wants to start from specific page, use that
            // Otherwise use last read page
            if (!$request->has('halaman')) {
                $halaman = $riwayatBacaan->halaman_terakhir;
            }
        }

        // Update or create reading history
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

        try {
            // This handles general book bookmark, not page-specific
            $bookmark = Bookmark::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->whereNull('halaman')
                ->first();

            if ($bookmark) {
                $bookmark->delete();
                return response()->json([
                    'bookmarked' => false,
                    'message' => 'Bookmark dihapus'
                ]);
            } else {
                Bookmark::create([
                    'user_id' => auth()->id(),
                    'buku_id' => $buku->id,
                    'halaman' => null // General bookmark
                ]);
                return response()->json([
                    'bookmarked' => true,
                    'message' => 'Bookmark ditambahkan'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error toggling bookmark: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat menyimpan bookmark'
            ], 500);
        }
    }

    public function addPageBookmark(Request $request, Buku $buku)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'halaman' => 'required|integer|min:1|max:' . $buku->halaman,
            'note' => 'nullable|string|max:255'
        ]);

        try {
            // Check if bookmark already exists for this page
            $existingBookmark = Bookmark::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->where('halaman', $request->halaman)
                ->first();

            if ($existingBookmark) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bookmark untuk halaman ini sudah ada'
                ]);
            }

            $bookmark = Bookmark::create([
                'user_id' => auth()->id(),
                'buku_id' => $buku->id,
                'halaman' => $request->halaman,
                'note' => $request->note
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bookmark berhasil ditambahkan',
                'bookmark' => $bookmark
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding page bookmark: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan bookmark'
            ], 500);
        }
    }

    public function getBookmarks(Buku $buku)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $bookmarks = Bookmark::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->whereNotNull('halaman') // Only page-specific bookmarks
                ->orderBy('halaman')
                ->get(['id', 'halaman', 'note', 'created_at']);

            return response()->json([
                'success' => true,
                'bookmarks' => $bookmarks
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting bookmarks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat bookmark'
            ], 500);
        }
    }

    public function deleteBookmark(Buku $buku, $bookmarkId)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $bookmark = Bookmark::where('id', $bookmarkId)
                ->where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->first();

            if (!$bookmark) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bookmark tidak ditemukan'
                ], 404);
            }

            $bookmark->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bookmark berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting bookmark: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus bookmark'
            ], 500);
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

        try {
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

            return response()->json([
                'message' => 'Rating berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving rating: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal menyimpan rating'
            ], 500);
        }
    }

    // Additional utility methods
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $books = Buku::where('judul', 'like', "%{$query}%")
                ->orWhere('penulis', 'like', "%{$query}%")
                ->select('id', 'judul', 'penulis', 'cover_gambar', 'jenis')
                ->limit(10)
                ->get();

            return response()->json($books);
        } catch (\Exception $e) {
            Log::error('Error searching books: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal mencari buku'
            ], 500);
        }
    }

    public function byKategori($kategoriId)
    {
        $kategori = \App\Models\Kategori::findOrFail($kategoriId);

        return redirect()->route('buku.index', [
            'kategori' => $kategoriId,
            'jenis' => $kategori->jenis
        ]);
    }

    public function byJenis($jenis)
    {
        return redirect()->route('buku.index', ['jenis' => $jenis]);
    }

    public function previewBuku(Buku $buku)
    {
        try {
            $filePath = 'public/' . $buku->file_path;

            if (!Storage::exists($filePath)) {
                return response()->json([
                    'error' => 'File tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'preview' => [
                    'title' => $buku->judul,
                    'author' => $buku->penulis,
                    'pages' => $buku->halaman,
                    'year' => $buku->tahun_terbit,
                    'description' => $buku->deskripsi,
                    'file_url' => Storage::url($buku->file_path),
                    'cover_url' => $buku->cover_gambar ? Storage::url($buku->cover_gambar) : null,
                    'categories' => $buku->kategoris->pluck('nama'),
                    'rating' => $buku->rating_rata_rata,
                    'total_ratings' => $buku->total_ratings
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting preview: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal memuat preview'
            ], 500);
        }
    }
}
