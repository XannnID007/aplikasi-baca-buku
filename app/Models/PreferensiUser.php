<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferensiUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_buku',
        'kategori_pilihan'
    ];

    protected $casts = [
        'kategori_pilihan' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
