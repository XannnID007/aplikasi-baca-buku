<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis',
        'deskripsi'
    ];

    public function bukus()
    {
        return $this->belongsToMany(Buku::class, 'buku_kategoris');
    }
}
