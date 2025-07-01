<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use App\Models\RiwayatBacaan;
use App\Models\Rating;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $totalBookmarks = Bookmark::where('user_id', $user->id)->count();
        $totalRiwayat = RiwayatBacaan::where('user_id', $user->id)->count();
        $totalRatings = Rating::where('user_id', $user->id)->count();

        return view('profile.index', compact('user', 'totalBookmarks', 'totalRiwayat', 'totalRatings'));
    }

    public function bookmarks()
    {
        $bookmarks = Bookmark::where('user_id', auth()->id())
            ->with('buku.kategoris')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('profile.bookmarks', compact('bookmarks'));
    }

    public function riwayatBacaan()
    {
        $riwayatBacaans = RiwayatBacaan::where('user_id', auth()->id())
            ->with('buku.kategoris')
            ->orderBy('terakhir_dibaca', 'desc')
            ->paginate(12);

        return view('profile.riwayat', compact('riwayatBacaans'));
    }

    public function ratings()
    {
        $ratings = Rating::where('user_id', auth()->id())
            ->with('buku.kategoris')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('profile.ratings', compact('ratings'));
    }
}
