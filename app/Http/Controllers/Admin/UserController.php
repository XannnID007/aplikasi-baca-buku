<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['bookmarks.buku', 'riwayatBacaans.buku', 'ratings.buku']);
        return view('admin.user.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'admin') {
            $user->delete();
            return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus!');
        }

        return redirect()->route('admin.user.index')->with('error', 'Tidak dapat menghapus admin!');
    }
}
