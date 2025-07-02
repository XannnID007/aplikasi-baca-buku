<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buku_id',
        'halaman',
        'note'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Buku
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    /**
     * Scope for page-specific bookmarks
     */
    public function scopeWithPage($query)
    {
        return $query->whereNotNull('halaman');
    }

    /**
     * Scope for general book bookmarks (without specific page)
     */
    public function scopeGeneral($query)
    {
        return $query->whereNull('halaman');
    }

    /**
     * Scope for user's bookmarks
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for book's bookmarks
     */
    public function scopeForBook($query, $bukuId)
    {
        return $query->where('buku_id', $bukuId);
    }

    /**
     * Get formatted bookmark info
     */
    public function getFormattedInfoAttribute()
    {
        if ($this->halaman) {
            return "Halaman {$this->halaman}" . ($this->note ? " - {$this->note}" : '');
        }

        return $this->note ?: 'Bookmark umum';
    }

    /**
     * Check if bookmark is for specific page
     */
    public function getIsPageBookmarkAttribute()
    {
        return !is_null($this->halaman);
    }

    /**
     * Get bookmark type
     */
    public function getTypeAttribute()
    {
        return $this->halaman ? 'page' : 'general';
    }

    /**
     * Get short note (truncated)
     */
    public function getShortNoteAttribute()
    {
        if (!$this->note) {
            return null;
        }

        return strlen($this->note) > 50 ? substr($this->note, 0, 50) . '...' : $this->note;
    }

    /**
     * Static method to create page bookmark
     */
    public static function createPageBookmark($userId, $bukuId, $halaman, $note = null)
    {
        return static::create([
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'halaman' => $halaman,
            'note' => $note
        ]);
    }

    /**
     * Static method to create general bookmark
     */
    public static function createGeneralBookmark($userId, $bukuId, $note = null)
    {
        return static::create([
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'note' => $note
        ]);
    }

    /**
     * Check if user has bookmarked specific page
     */
    public static function hasPageBookmark($userId, $bukuId, $halaman)
    {
        return static::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->where('halaman', $halaman)
            ->exists();
    }

    /**
     * Check if user has general bookmark for book
     */
    public static function hasGeneralBookmark($userId, $bukuId)
    {
        return static::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->whereNull('halaman')
            ->exists();
    }

    /**
     * Get user's bookmarks for a book ordered by page
     */
    public static function getBookBookmarks($userId, $bukuId)
    {
        return static::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->orderBy('halaman')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
