<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategoris')->paginate(10);
        return view('admin.buku.index', compact('bukus'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'cover_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_buku' => 'required|file|mimes:pdf,epub|max:10240',
            'halaman' => 'required|integer|min:1',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'jenis' => 'required|in:fiksi,non_fiksi',
            'kategori_ids' => 'required|array|min:1',
            'kategori_ids.*' => 'exists:kategoris,id'
        ]);

        $buku = new Buku();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->deskripsi = $request->deskripsi;
        $buku->halaman = $request->halaman;
        $buku->tahun_terbit = $request->tahun_terbit;
        $buku->jenis = $request->jenis;

        if ($request->hasFile('cover_gambar')) {
            $buku->cover_gambar = $request->file('cover_gambar')->store('covers', 'public');
        }

        if ($request->hasFile('file_buku')) {
            $buku->file_path = $request->file('file_buku')->store('books', 'public');
        }

        $buku->save();
        $buku->kategoris()->attach($request->kategori_ids);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        $buku->load('kategoris', 'ratings.user');
        return view('admin.buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::all();
        $selectedKategoris = $buku->kategoris->pluck('id')->toArray();
        return view('admin.buku.edit', compact('buku', 'kategoris', 'selectedKategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'cover_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'file_buku' => 'nullable|file|mimes:pdf,epub|max:10240',
            'halaman' => 'required|integer|min:1',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'jenis' => 'required|in:fiksi,non_fiksi',
            'kategori_ids' => 'required|array|min:1',
            'kategori_ids.*' => 'exists:kategoris,id'
        ]);

        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->deskripsi = $request->deskripsi;
        $buku->halaman = $request->halaman;
        $buku->tahun_terbit = $request->tahun_terbit;
        $buku->jenis = $request->jenis;

        if ($request->hasFile('cover_gambar')) {
            if ($buku->cover_gambar) {
                Storage::disk('public')->delete($buku->cover_gambar);
            }
            $buku->cover_gambar = $request->file('cover_gambar')->store('covers', 'public');
        }

        if ($request->hasFile('file_buku')) {
            if ($buku->file_path) {
                Storage::disk('public')->delete($buku->file_path);
            }
            $buku->file_path = $request->file('file_buku')->store('books', 'public');
        }

        $buku->save();
        $buku->kategoris()->sync($request->kategori_ids);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover_gambar) {
            Storage::disk('public')->delete($buku->cover_gambar);
        }
        if ($buku->file_path) {
            Storage::disk('public')->delete($buku->file_path);
        }

        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
