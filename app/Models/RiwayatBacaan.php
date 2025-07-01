<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBacaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buku_id',
        'halaman_terakhir',
        'terakhir_dibaca'
    ];

    protected $casts = [
        'terakhir_dibaca' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
