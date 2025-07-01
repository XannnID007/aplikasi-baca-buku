<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'deskripsi',
        'cover_gambar',
        'file_path',
        'halaman',
        'tahun_terbit',
        'jenis',
        'views',
        'rating_rata_rata',
        'total_ratings'
    ];

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'buku_kategoris');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function riwayatBacaans()
    {
        return $this->hasMany(RiwayatBacaan::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function updateRating()
    {
        $ratings = $this->ratings;
        $this->total_ratings = $ratings->count();
        $this->rating_rata_rata = $ratings->count() > 0 ? $ratings->avg('rating') : 0;
        $this->save();
    }
}
