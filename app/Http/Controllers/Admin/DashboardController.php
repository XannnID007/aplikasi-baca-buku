<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\User;
use App\Models\Kategori;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalUser = User::where('role', 'user')->count();
        $totalKategori = Kategori::count();
        $bukuPopuler = Buku::orderBy('views', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalBuku', 'totalUser', 'totalKategori', 'bukuPopuler'));
    }
}
