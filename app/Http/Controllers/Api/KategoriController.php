<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Get categories by type (fiksi/non_fiksi)
     */
    public function getByJenis($jenis)
    {
        $kategoris = Kategori::where('jenis', $jenis)
            ->orderBy('nama')
            ->get(['id', 'nama', 'deskripsi']);

        return response()->json($kategoris);
    }

    /**
     * Get all categories with book count
     */
    public function getAllWithCount()
    {
        $kategoris = Kategori::withCount('bukus')
            ->orderBy('nama')
            ->get();

        return response()->json($kategoris);
    }
}
