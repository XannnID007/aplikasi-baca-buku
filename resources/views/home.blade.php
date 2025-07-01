@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <div class="hero">
        <h1><i class="fas fa-book-open"></i> Selamat Datang di Perpustakaan Digital</h1>
        <p>Temukan ribuan buku gratis dengan sistem rekomendasi cerdas menggunakan teknologi K-means clustering. Baca,
            bookmark, dan nikmati pengalaman membaca yang personal.</p>

        <div style="margin-top: 40px;">
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary"
                    style="margin-right: 15px; padding: 15px 30px; font-size: 18px;">
                    <i class="fas fa-user-plus"></i> Daftar Gratis
                </a>
                <a href="{{ route('pilih.jenis') }}" class="btn btn-outline" style="padding: 15px 30px; font-size: 18px;">
                    <i class="fas fa-magic"></i> Coba Rekomendasi
                </a>
            @else
                <a href="{{ route('pilih.jenis') }}" class="btn btn-primary"
                    style="margin-right: 15px; padding: 15px 30px; font-size: 18px;">
                    <i class="fas fa-magic"></i> Dapatkan Rekomendasi
                </a>
                <a href="{{ route('buku.index') }}" class="btn btn-outline" style="padding: 15px 30px; font-size: 18px;">
                    <i class="fas fa-books"></i> Jelajahi Katalog
                </a>
            @endguest
        </div>
    </div>

    <!-- Search Section -->
    <div class="card">
        <div class="search-container">
            <form action="{{ route('buku.index') }}" method="GET">
                <input type="text" name="search" class="search-input"
                    placeholder="Cari buku berdasarkan judul, penulis, atau deskripsi..." value="{{ request('search') }}">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 20px; flex-wrap: wrap;">
            <a href="{{ route('buku.index', ['jenis' => 'fiksi']) }}" class="btn btn-outline btn-sm">
                <i class="fas fa-dragon"></i> Buku Fiksi
            </a>
            <a href="{{ route('buku.index', ['jenis' => 'non_fiksi']) }}" class="btn btn-outline btn-sm">
                <i class="fas fa-graduation-cap"></i> Buku Non-Fiksi
            </a>
            <a href="{{ route('buku.index', ['sort' => 'newest']) }}" class="btn btn-outline btn-sm">
                <i class="fas fa-clock"></i> Terbaru
            </a>
            <a href="{{ route('buku.index', ['sort' => 'popular']) }}" class="btn btn-outline btn-sm">
                <i class="fas fa-fire"></i> Populer
            </a>
        </div>
    </div>

    <!-- Popular Books Section -->
    @if ($bukuPopuler->count() > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-fire"></i> Buku Paling Populer
                </h2>
                <p class="card-subtitle">Buku yang paling banyak dibaca oleh pengguna kami</p>
            </div>

            <div class="book-grid">
                @foreach ($bukuPopuler as $buku)
                    <div class="book-card" data-book-id="{{ $buku->id }}">
                        <div class="book-cover">
                            @if ($buku->cover_gambar)
                                <img src="{{ Storage::url($buku->cover_gambar) }}" alt="{{ $buku->judul }}">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif

                            <!-- Popular Badge -->
                            <div
                                style="position: absolute; top: 10px; left: 10px; background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                <i class="fas fa-fire"></i> Popular
                            </div>

                            @auth
                                <div style="position: absolute; top: 10px; right: 10px;">
                                    <button onclick="toggleBookmark({{ $buku->id }})"
                                        class="bookmark-btn {{ \App\Models\Bookmark::where('user_id', auth()->id())->where('buku_id', $buku->id)->exists()? 'bookmarked': '' }}"
                                        style="background: rgba(255,255,255,0.9); border: none; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i
                                            class="{{ \App\Models\Bookmark::where('user_id', auth()->id())->where('buku_id', $buku->id)->exists()? 'fas': 'far' }} fa-bookmark"></i>
                                    </button>
                                </div>
                            @endauth
                        </div>

                        <div class="book-info">
                            <h3 class="book-title">{{ $buku->judul }}</h3>
                            <p class="book-author">{{ $buku->penulis }}</p>

                            <div class="book-meta">
                                <span><i class="fas fa-eye"></i> {{ number_format($buku->views) }}</span>
                                <span><i class="fas fa-calendar"></i> {{ $buku->tahun_terbit }}</span>
                            </div>

                            <div class="book-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($buku->rating_rata_rata))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $buku->rating_rata_rata)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span style="margin-left: 8px; color: #546e7a; font-size: 14px;">
                                    ({{ number_format($buku->rating_rata_rata, 1) }})
                                </span>
                            </div>

                            <div class="book-categories">
                                @foreach ($buku->kategoris->take(2) as $kategori)
                                    <span class="category-tag">{{ $kategori->nama }}</span>
                                @endforeach
                                @if ($buku->kategoris->count() > 2)
                                    <span class="category-tag"
                                        style="background: #e0e0e0; color: #666;">+{{ $buku->kategoris->count() - 2 }}</span>
                                @endif
                            </div>

                            <div class="book-actions">
                                <a href="{{ route('buku.show', $buku) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                @auth
                                    <a href="{{ route('buku.baca', $buku) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-book-open"></i> Baca
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-sign-in-alt"></i> Login
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('buku.index', ['sort' => 'popular']) }}" class="btn btn-outline">
                    <i class="fas fa-arrow-right"></i> Lihat Semua Buku Populer
                </a>
            </div>
        </div>
    @endif

    <!-- Latest Books Section -->
    @if ($bukuTerbaru->count() > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-clock"></i> Buku Terbaru
                </h2>
                <p class="card-subtitle">Koleksi terbaru yang baru saja ditambahkan</p>
            </div>

            <div class="book-grid">
                @foreach ($bukuTerbaru as $buku)
                    <div class="book-card" data-book-id="{{ $buku->id }}">
                        <div class="book-cover">
                            @if ($buku->cover_gambar)
                                <img src="{{ Storage::url($buku->cover_gambar) }}" alt="{{ $buku->judul }}">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif

                            <!-- New Badge -->
                            <div
                                style="position: absolute; top: 10px; left: 10px; background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                <i class="fas fa-sparkles"></i> Baru
                            </div>

                            @auth
                                <div style="position: absolute; top: 10px; right: 10px;">
                                    <button onclick="toggleBookmark({{ $buku->id }})"
                                        class="bookmark-btn {{ \App\Models\Bookmark::where('user_id', auth()->id())->where('buku_id', $buku->id)->exists()? 'bookmarked': '' }}"
                                        style="background: rgba(255,255,255,0.9); border: none; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i
                                            class="{{ \App\Models\Bookmark::where('user_id', auth()->id())->where('buku_id', $buku->id)->exists()? 'fas': 'far' }} fa-bookmark"></i>
                                    </button>
                                </div>
                            @endauth
                        </div>

                        <div class="book-info">
                            <h3 class="book-title">{{ $buku->judul }}</h3>
                            <p class="book-author">{{ $buku->penulis }}</p>

                            <div class="book-meta">
                                <span><i class="fas fa-file-alt"></i> {{ $buku->halaman }} hal</span>
                                <span><i class="fas fa-calendar"></i> {{ $buku->tahun_terbit }}</span>
                            </div>

                            <div class="book-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($buku->rating_rata_rata))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $buku->rating_rata_rata)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span style="margin-left: 8px; color: #546e7a; font-size: 14px;">
                                    ({{ $buku->total_ratings > 0 ? number_format($buku->rating_rata_rata, 1) : 'Belum ada rating' }})
                                </span>
                            </div>

                            <div class="book-categories">
                                @foreach ($buku->kategoris->take(2) as $kategori)
                                    <span class="category-tag">{{ $kategori->nama }}</span>
                                @endforeach
                                @if ($buku->kategoris->count() > 2)
                                    <span class="category-tag"
                                        style="background: #e0e0e0; color: #666;">+{{ $buku->kategoris->count() - 2 }}</span>
                                @endif
                            </div>

                            <div class="book-actions">
                                <a href="{{ route('buku.show', $buku) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                @auth
                                    <a href="{{ route('buku.baca', $buku) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-book-open"></i> Baca
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-sign-in-alt"></i> Login
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('buku.index', ['sort' => 'newest']) }}" class="btn btn-outline">
                    <i class="fas fa-arrow-right"></i> Lihat Semua Buku Terbaru
                </a>
            </div>
        </div>
    @endif

    <!-- Features Section -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-star"></i> Mengapa Memilih Kami?
            </h2>
            <p class="card-subtitle">Fitur-fitur unggulan yang membuat pengalaman membaca Anda lebih baik</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div style="text-align: center; padding: 30px;">
                <div style="font-size: 64px; color: #42a5f5; margin-bottom: 20px;">
                    <i class="fas fa-robot"></i>
                </div>
                <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 24px;">Rekomendasi Cerdas</h3>
                <p style="color: #546e7a; line-height: 1.6;">
                    Sistem rekomendasi menggunakan algoritma K-means clustering yang menganalisis preferensi Anda untuk
                    memberikan saran buku yang tepat.
                </p>
            </div>

            <div style="text-align: center; padding: 30px;">
                <div style="font-size: 64px; color: #66bb6a; margin-bottom: 20px;">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 24px;">Reading Tracker</h3>
                <p style="color: #546e7a; line-height: 1.6;">
                    Simpan progress bacaan Anda, bookmark halaman favorit, dan lanjutkan membaca dari halaman terakhir kapan
                    saja.
                </p>
            </div>

            <div style="text-align: center; padding: 30px;">
                <div style="font-size: 64px; color: #ff7043; margin-bottom: 20px;">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 style="color: #2c3e50; margin-bottom: 15px; font-size: 24px;">Gratis Selamanya</h3>
                <p style="color: #546e7a; line-height: 1.6;">
                    Akses penuh ke ribuan buku dari Project Gutenberg dan Open Library tanpa biaya berlangganan apapun.
                </p>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="card" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: none;">
        <div style="text-align: center; padding: 40px 20px;">
            <h2 style="color: #1976d2; margin-bottom: 30px; font-size: 28px;">
                <i class="fas fa-chart-line"></i> Statistik Perpustakaan
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px;">
                <div>
                    <div style="font-size: 48px; color: #1976d2; font-weight: bold; margin-bottom: 10px;">
                        {{ number_format(\App\Models\Buku::count()) }}
                    </div>
                    <div style="color: #546e7a; font-weight: 500;">Total Buku</div>
                </div>

                <div>
                    <div style="font-size: 48px; color: #388e3c; font-weight: bold; margin-bottom: 10px;">
                        {{ number_format(\App\Models\User::where('role', 'user')->count()) }}
                    </div>
                    <div style="color: #546e7a; font-weight: 500;">Pembaca Aktif</div>
                </div>

                <div>
                    <div style="font-size: 48px; color: #f57c00; font-weight: bold; margin-bottom: 10px;">
                        {{ number_format(\App\Models\Buku::sum('views')) }}
                    </div>
                    <div style="color: #546e7a; font-weight: 500;">Total Bacaan</div>
                </div>

                <div>
                    <div style="font-size: 48px; color: #d32f2f; font-weight: bold; margin-bottom: 10px;">
                        {{ number_format(\App\Models\Rating::count()) }}
                    </div>
                    <div style="color: #546e7a; font-weight: 500;">Total Rating</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    @guest
        <div class="card"
            style="text-align: center; background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%); border: none; color: white;">
            <div style="padding: 60px 20px;">
                <h2 style="color: white; margin-bottom: 20px; font-size: 32px;">
                    <i class="fas fa-rocket"></i> Mulai Petualangan Membaca Anda
                </h2>
                <p
                    style="color: rgba(255,255,255,0.9); font-size: 18px; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Bergabunglah dengan ribuan pembaca lainnya dan dapatkan akses ke koleksi buku gratis dengan sistem
                    rekomendasi personal.
                </p>

                <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="btn"
                        style="background: white; color: #1976d2; padding: 15px 30px; font-size: 18px; font-weight: 600;">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </a>
                    <a href="{{ route('pilih.jenis') }}" class="btn btn-outline"
                        style="border-color: white; color: white; padding: 15px 30px; font-size: 18px;">
                        <i class="fas fa-magic"></i> Coba Rekomendasi
                    </a>
                </div>
            </div>
        </div>
    @endguest

    <script>
        // Animate elements on scroll
        function animateOnScroll() {
            const cards = document.querySelectorAll('.card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        }

        // Initialize animations when page loads
        document.addEventListener('DOMContentLoaded', function() {
            animateOnScroll();

            // Animate statistics counters
            const statNumbers = document.querySelectorAll('[style*="font-size: 48px"]');
            statNumbers.forEach(stat => {
                const target = parseInt(stat.textContent.replace(/,/g, ''));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        stat.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(current).toLocaleString();
                    }
                }, 40);
            });
        });

        // Search form enhancement
        const searchForm = document.querySelector('.search-container form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const searchInput = this.querySelector('input[name="search"]');
                if (searchInput.value.trim().length < 2) {
                    e.preventDefault();
                    searchInput.focus();
                    showAlert('warning', 'Masukkan minimal 2 karakter untuk pencarian');
                }
            });
        }

        // Book card hover effects
        document.querySelectorAll('.book-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
@endsection
