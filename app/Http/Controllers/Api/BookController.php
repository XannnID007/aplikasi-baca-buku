<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\RiwayatBacaan;

class BookController extends Controller
{
    /**
     * Update reading progress
     */
    public function updateProgress(Request $request, Buku $buku)
    {
        $request->validate([
            'halaman_terakhir' => 'required|integer|min:1|max:' . $buku->halaman
        ]);

        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        RiwayatBacaan::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'buku_id' => $buku->id
            ],
            [
                'halaman_terakhir' => $request->halaman_terakhir,
                'terakhir_dibaca' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil disimpan',
            'halaman' => $request->halaman_terakhir
        ]);
    }

    /**
     * Track book view for analytics
     */
    public function trackView(Request $request, Buku $buku)
    {
        // Increment view count
        $buku->increment('views');

        return response()->json([
            'success' => true,
            'views' => $buku->fresh()->views
        ]);
    }

    /**
     * Search books for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $books = Buku::where('judul', 'like', "%{$query}%")
            ->orWhere('penulis', 'like', "%{$query}%")
            ->select('id', 'judul', 'penulis', 'cover_gambar')
            ->limit(10)
            ->get();

        return response()->json($books);
    }

    /**
     * Get book content for reader
     */
    public function getContent(Request $request, Buku $buku)
    {
        $page = $request->get('page', 1);

        // This is a placeholder - implement actual content reading based on your file format
        $content = $this->generatePageContent($buku, $page);

        return response()->json([
            'content' => $content,
            'page' => $page,
            'total_pages' => $buku->halaman
        ]);
    }

    /**
     * Generate sample page content (replace with actual implementation)
     */
    private function generatePageContent($buku, $page)
    {
        // This would normally read from the actual book file
        return [
            'title' => "Halaman {$page} - {$buku->judul}",
            'text' => "Ini adalah konten halaman {$page} dari buku {$buku->judul}. Konten ini harus dibaca dari file PDF atau EPUB yang sebenarnya.",
            'page' => $page
        ];
    }
}
