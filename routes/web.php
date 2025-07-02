<?php
// routes/web.php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BukuController as AdminBukuController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\BookController as ApiBookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Book recommendation system routes
Route::get('/pilih-jenis', [HomeController::class, 'pilihJenis'])->name('pilih.jenis');
Route::post('/pilih-jenis', [HomeController::class, 'prosesJenis'])->name('pilih.jenis.proses');
Route::get('/pilih-kategori', [HomeController::class, 'pilihKategori'])->name('pilih.kategori');
Route::post('/pilih-kategori', [HomeController::class, 'prosesKategori'])->name('pilih.kategori.proses');

// Public book routes
Route::prefix('buku')->name('buku.')->group(function () {
    Route::get('/', [BukuController::class, 'index'])->name('index');
    Route::get('/{buku}', [BukuController::class, 'show'])->name('show');

    // Authenticated book routes
    Route::middleware('auth')->group(function () {
        Route::get('/{buku}/baca', [BukuController::class, 'baca'])->name('baca');
        Route::post('/{buku}/bookmark', [BukuController::class, 'toggleBookmark'])->name('bookmark');
        Route::post('/{buku}/rating', [BukuController::class, 'rating'])->name('rating');

        // Page-specific bookmark
        Route::post('/{buku}/bookmark-page', [BukuController::class, 'addPageBookmark'])->name('bookmark.page');
        Route::get('/{buku}/bookmarks', [BukuController::class, 'getBookmarks'])->name('bookmarks');
        Route::delete('/{buku}/bookmarks/{bookmark}', [BukuController::class, 'deleteBookmark'])->name('bookmark.delete');
    });
});

// Download route
Route::get('/download/{buku}', [ApiBookController::class, 'downloadBook'])
    ->name('download.buku')
    ->middleware('auth');

// User profile routes
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/bookmarks', [ProfileController::class, 'bookmarks'])->name('bookmarks');
    Route::get('/riwayat', [ProfileController::class, 'riwayatBacaan'])->name('riwayat');
    Route::get('/ratings', [ProfileController::class, 'ratings'])->name('ratings');
});

// Authentication routes
Auth::routes();

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/kategori/by-jenis/{jenis}', [AdminKategoriController::class, 'getByJenis'])
        ->name('kategori.by-jenis');
    // Buku management
    Route::resource('buku', AdminBukuController::class);

    // Kategori management
    Route::resource('kategori', AdminKategoriController::class);

    // User management
    Route::resource('user', AdminUserController::class)->except(['create', 'store', 'edit', 'update']);

    // Laporan routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/buku', [AdminLaporanController::class, 'laporanBuku'])->name('buku');
        Route::get('/user', [AdminLaporanController::class, 'laporanUser'])->name('user');
        Route::get('/rating', [AdminLaporanController::class, 'laporanRating'])->name('rating');
        Route::get('/populer', [AdminLaporanController::class, 'laporanPopuler'])->name('populer');
    });
});

// API routes for AJAX requests
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    // Reading progress
    Route::post('/buku/{buku}/update-progress', [ApiBookController::class, 'updateProgress'])->name('update.progress');

    // Book interaction
    Route::post('/buku/{buku}/view', [ApiBookController::class, 'trackView'])->name('track.view');
    Route::get('/buku/{buku}/pdf-info', [ApiBookController::class, 'getPdfFile'])->name('pdf.info');
    Route::get('/buku/{buku}/stats', [ApiBookController::class, 'getReadingStats'])->name('reading.stats');
    Route::get('/buku/{buku}/preview', [ApiBookController::class, 'getPreview'])->name('preview');

    // Bookmarks API
    Route::post('/buku/{buku}/bookmark-page', [ApiBookController::class, 'addPageBookmark'])->name('bookmark.page');
    Route::get('/buku/{buku}/bookmarks', [ApiBookController::class, 'getBookmarks'])->name('bookmarks');
    Route::delete('/buku/{buku}/bookmarks/{bookmark}', [ApiBookController::class, 'deleteBookmark'])->name('bookmark.delete');

    // Search
    Route::get('/search-books', [ApiBookController::class, 'search'])->name('search.books');
    Route::get('/recommendations', [HomeController::class, 'getRecommendations'])->name('recommendations');
});

// Additional utility routes
Route::get('/preview/{buku}', [BukuController::class, 'previewBuku'])->name('preview.buku');

// Search and filter routes
Route::get('/search', [BukuController::class, 'search'])->name('search');
Route::get('/kategori/{kategori}', [BukuController::class, 'byKategori'])->name('kategori.show');
Route::get('/jenis/{jenis}', [BukuController::class, 'byJenis'])->name('jenis.show');

// CORS preflight handling for API routes
Route::options('api/{any}', function () {
    return response('', 200)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN');
})->where('any', '.*');
