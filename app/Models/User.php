<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan role ke fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Scope to get only admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope to get only regular users
     */
    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Relationship with bookmarks
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Relationship with reading history
     */
    public function riwayatBacaans()
    {
        return $this->hasMany(RiwayatBacaan::class);
    }

    /**
     * Relationship with ratings
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relationship with user preferences
     */
    public function preferensiUser()
    {
        return $this->hasOne(PreferensiUser::class);
    }

    /**
     * Get user's favorite categories based on reading history
     */
    public function getFavoriteCategoriesAttribute()
    {
        return $this->riwayatBacaans()
            ->with('buku.kategoris')
            ->get()
            ->pluck('buku.kategoris')
            ->flatten()
            ->groupBy('id')
            ->map(function ($kategoris) {
                return [
                    'kategori' => $kategoris->first(),
                    'count' => $kategoris->count()
                ];
            })
            ->sortByDesc('count')
            ->take(5);
    }

    /**
     * Get user's reading statistics
     */
    public function getReadingStatsAttribute()
    {
        return [
            'total_books_read' => $this->riwayatBacaans()->count(),
            'total_bookmarks' => $this->bookmarks()->count(),
            'total_ratings' => $this->ratings()->count(),
            'average_rating_given' => $this->ratings()->avg('rating'),
            'reading_streak_days' => $this->calculateReadingStreak(),
            'favorite_genre' => $this->getMostReadGenre(),
        ];
    }

    /**
     * Calculate reading streak in days
     */
    private function calculateReadingStreak()
    {
        $recentReadings = $this->riwayatBacaans()
            ->orderBy('terakhir_dibaca', 'desc')
            ->pluck('terakhir_dibaca')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->unique()
            ->values();

        if ($recentReadings->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $currentDate = now()->format('Y-m-d');

        foreach ($recentReadings as $index => $readingDate) {
            $expectedDate = now()->subDays($index)->format('Y-m-d');

            if ($readingDate === $expectedDate) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Get most read genre
     */
    private function getMostReadGenre()
    {
        $genreCount = $this->riwayatBacaans()
            ->with('buku')
            ->get()
            ->groupBy('buku.jenis')
            ->map(function ($books) {
                return $books->count();
            });

        if ($genreCount->isEmpty()) {
            return null;
        }

        $mostRead = $genreCount->sortDesc()->keys()->first();

        return $mostRead === 'fiksi' ? 'Fiksi' : 'Non-Fiksi';
    }

    /**
     * Get user's recent activity
     */
    public function getRecentActivityAttribute()
    {
        $activities = collect();

        // Recent readings
        $this->riwayatBacaans()->with('buku')
            ->latest('terakhir_dibaca')
            ->take(5)
            ->get()
            ->each(function ($riwayat) use ($activities) {
                $activities->push([
                    'type' => 'reading',
                    'description' => "Membaca {$riwayat->buku->judul}",
                    'date' => $riwayat->terakhir_dibaca,
                    'icon' => 'book-open'
                ]);
            });

        // Recent ratings
        $this->ratings()->with('buku')
            ->latest('created_at')
            ->take(3)
            ->get()
            ->each(function ($rating) use ($activities) {
                $activities->push([
                    'type' => 'rating',
                    'description' => "Memberikan rating {$rating->rating} bintang untuk {$rating->buku->judul}",
                    'date' => $rating->created_at,
                    'icon' => 'star'
                ]);
            });

        // Recent bookmarks
        $this->bookmarks()->with('buku')
            ->latest('created_at')
            ->take(3)
            ->get()
            ->each(function ($bookmark) use ($activities) {
                $activities->push([
                    'type' => 'bookmark',
                    'description' => "Menambah bookmark {$bookmark->buku->judul}",
                    'date' => $bookmark->created_at,
                    'icon' => 'bookmark'
                ]);
            });

        return $activities->sortByDesc('date')->take(10)->values();
    }
}
