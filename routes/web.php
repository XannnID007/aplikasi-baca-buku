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
    });
});

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
    Route::post('/buku/{buku}/update-progress', [BukuController::class, 'updateProgress'])->name('update.progress');
    Route::get('/recommendations', [HomeController::class, 'getRecommendations'])->name('recommendations');
    Route::get('/search-books', [BukuController::class, 'searchBooks'])->name('search.books');
});

// Additional utility routes
Route::get('/download/{buku}', [BukuController::class, 'downloadBuku'])->name('download.buku')->middleware('auth');
Route::get('/preview/{buku}', [BukuController::class, 'previewBuku'])->name('preview.buku');

// Search and filter routes
Route::get('/search', [BukuController::class, 'search'])->name('search');
Route::get('/kategori/{kategori}', [BukuController::class, 'byKategori'])->name('kategori.show');
Route::get('/jenis/{jenis}', [BukuController::class, 'byJenis'])->name('jenis.show');
