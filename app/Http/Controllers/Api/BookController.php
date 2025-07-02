<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\RiwayatBacaan;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        try {
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
        } catch (\Exception $e) {
            Log::error('Error updating reading progress: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan progress'
            ], 500);
        }
    }

    /**
     * Track book view for analytics
     */
    public function trackView(Request $request, Buku $buku)
    {
        try {
            // Increment view count
            $buku->increment('views');

            return response()->json([
                'success' => true,
                'views' => $buku->fresh()->views
            ]);
        } catch (\Exception $e) {
            Log::error('Error tracking view: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal melacak view'
            ], 500);
        }
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

    /**
     * Get PDF file for viewer
     */
    public function getPdfFile(Buku $buku)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $filePath = 'public/' . $buku->file_path;
            
            if (!Storage::exists($filePath)) {
                return response()->json([
                    'error' => 'File tidak ditemukan'
                ], 404);
            }

            // Get file info
            $fileSize = Storage::size($filePath);
            $mimeType = Storage::mimeType($filePath);
            
            // Validate file type
            if (!in_array($mimeType, ['application/pdf'])) {
                return response()->json([
                    'error' => 'File bukan format PDF'
                ], 400);
            }

            // Return file info for frontend
            return response()->json([
                'success' => true,
                'file_url' => Storage::url($buku->file_path),
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'pages' => $buku->halaman
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting PDF file: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal memuat file PDF'
            ], 500);
        }
    }

    /**
     * Add bookmark for specific page
     */
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

    /**
     * Get user bookmarks for a book
     */
    public function getBookmarks(Buku $buku)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $bookmarks = Bookmark::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
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

    /**
     * Delete bookmark
     */
    public function deleteBookmark(Request $request, Buku $buku, $bookmarkId)
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

    /**
     * Get reading statistics
     */
    public function getReadingStats(Buku $buku)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $riwayat = RiwayatBacaan::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->first();

            $bookmarks = Bookmark::where('user_id', auth()->id())
                ->where('buku_id', $buku->id)
                ->count();

            $progress = 0;
            if ($riwayat && $buku->halaman > 0) {
                $progress = ($riwayat->halaman_terakhir / $buku->halaman) * 100;
            }

            return response()->json([
                'success' => true,
                'stats' => [
                    'current_page' => $riwayat ? $riwayat->halaman_terakhir : 1,
                    'total_pages' => $buku->halaman,
                    'progress_percentage' => round($progress, 2),
                    'bookmarks_count' => $bookmarks,
                    'last_read' => $riwayat ? $riwayat->terakhir_dibaca : null,
                    'reading_time' => $this->calculateReadingTime($riwayat)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting reading stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik'
            ], 500);
        }
    }

    /**
     * Calculate estimated reading time
     */
    private function calculateReadingTime($riwayat)
    {
        if (!$riwayat) {
            return null;
        }

        // Assume average reading speed of 250 words per minute
        // and estimate 300 words per page
        $wordsPerPage = 300;
        $readingSpeedWPM = 250;
        
        $totalWords = $riwayat->buku->halaman * $wordsPerPage;
        $estimatedMinutes = $totalWords / $readingSpeedWPM;
        
        $currentWords = $riwayat->halaman_terakhir * $wordsPerPage;
        $readMinutes = $currentWords / $readingSpeedWPM;
        
        return [
            'estimated_total_minutes' => round($estimatedMinutes),
            'read_minutes' => round($readMinutes),
            'remaining_minutes' => round($estimatedMinutes - $readMinutes)
        ];
    }

    /**
     * Download book file
     */
    public function downloadBook(Buku $buku)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        try {
            $filePath = 'public/' . $buku->file_path;
            
            if (!Storage::exists($filePath)) {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }

            // Log download activity
            Log::info('Book downloaded', [
                'user_id' => auth()->id(),
                'book_id' => $buku->id,
                'book_title' => $buku->judul
            ]);

            // Return download response
            return Storage::download($filePath, $buku->judul . '.pdf');

        } catch (\Exception $e) {
            Log::error('Error downloading book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mendownload file');
        }
    }

    /**
     * Get book content preview (first few pages)
     */
    public function getPreview(Buku $buku)
    {
        try {
            $filePath = 'public/' . $buku->file_path;
            
            if (!Storage::exists($filePath)) {
                return response()->json([
                    'error' => 'File tidak ditemukan'
                ], 404);
            }

            // Return basic info for preview
            return response()->json([
                'success' => true,
                'preview' => [
                    'title' => $buku->judul,
                    'author' => $buku->penulis,
                    'pages' => $buku->halaman,
                    'year' => $buku->tahun_terbit,
                    'description' => $buku->deskripsi,
                    'file_url' => Storage::url($buku->file_path),
                    'cover_url' => $buku->cover_gambar ? Storage::url($buku->cover_gambar) : null
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