@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
    <!-- Hero Section -->
    <div class="hero" style="padding: 40px 20px;">
        <h1><i class="fas fa-books"></i> Katalog Lengkap</h1>
        <p>Jelajahi ribuan buku gratis dari berbagai genre dan kategori</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="card">
        <div style="padding: 30px;">
            <!-- Search Bar -->
            <div class="search-container" style="margin-bottom: 30px;">
                <form action="{{ route('buku.index') }}" method="GET" id="searchForm">
                    <input type="text" name="search" class="search-input"
                        placeholder="Cari buku berdasarkan judul, penulis, atau deskripsi..."
                        value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Hidden inputs to maintain other filters -->
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                </form>
            </div>

            <!-- Filter Tabs -->
            <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
                <a href="{{ route('buku.index') }}"
                    class="btn {{ !request()->hasAny(['jenis', 'kategori']) ? 'btn-primary' : 'btn-outline' }}">
                    <i class="fas fa-list"></i> Semua Buku ({{ \App\Models\Buku::count() }})
                </a>
                <a href="{{ route('buku.index', ['jenis' => 'fiksi'] + request()->except('jenis')) }}"
                    class="btn {{ request('jenis') == 'fiksi' ? 'btn-primary' : 'btn-outline' }}">
                    <i class="fas fa-dragon"></i> Fiksi ({{ \App\Models\Buku::where('jenis', 'fiksi')->count() }})
                </a>
                <a href="{{ route('buku.index', ['jenis' => 'non_fiksi'] + request()->except('jenis')) }}"
                    class="btn {{ request('jenis') == 'non_fiksi' ? 'btn-primary' : 'btn-outline' }}">
                    <i class="fas fa-graduation-cap"></i> Non-Fiksi
                    ({{ \App\Models\Buku::where('jenis', 'non_fiksi')->count() }})
                </a>
            </div>

            <!-- Advanced Filters -->
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <!-- Category Filter -->
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Kategori</label>
                    <select name="kategori" class="form-control" onchange="applyFilter()">
                        <option value="">Semua Kategori</option>
                        @if (request('jenis'))
                            @foreach (\App\Models\Kategori::where('jenis', request('jenis'))->get() as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        @else
                            @foreach (\App\Models\Kategori::all() as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }} ({{ ucfirst(str_replace('_', ' ', $kategori->jenis)) }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Sort Filter -->
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Urutkan</label>
                    <select name="sort" class="form-control" onchange="applyFilter()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Paling Populer
                        </option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi
                        </option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul Z-A
                        </option>
                        <option value="year_desc" {{ request('sort') == 'year_desc' ? 'selected' : '' }}>Tahun Terbaru
                        </option>
                        <option value="year_asc" {{ request('sort') == 'year_asc' ? 'selected' : '' }}>Tahun Terlama
                        </option>
                    </select>
                </div>

                <!-- Rating Filter -->
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #2c3e50; font-weight: 500;">Rating
                        Minimal</label>
                    <select name="rating" class="form-control" onchange="applyFilter()">
                        <option value="">Semua Rating</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Bintang</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Bintang</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2+ Bintang</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <div style="display: flex; align-items: end;">
                    <a href="{{ route('buku.index') }}" class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-undo"></i> Reset Filter
                    </a>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if (request()->hasAny(['search', 'jenis', 'kategori', 'sort', 'rating']))
                <div style="margin-bottom: 20px;">
                    <h6 style="color: #546e7a; margin-bottom: 10px;">Filter Aktif:</h6>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        @if (request('search'))
                            <span
                                style="background: #e3f2fd; color: #1976d2; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: flex; align-items: center;">
                                <i class="fas fa-search" style="margin-right: 5px;"></i>
                                "{{ request('search') }}"
                                <a href="{{ route('buku.index', request()->except('search')) }}"
                                    style="margin-left: 8px; color: #1976d2;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif

                        @if (request('jenis'))
                            <span
                                style="background: #e8f5e8; color: #388e3c; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: flex; align-items: center;">
                                <i class="fas fa-tag" style="margin-right: 5px;"></i>
                                {{ ucfirst(str_replace('_', ' ', request('jenis'))) }}
                                <a href="{{ route('buku.index', request()->except('jenis')) }}"
                                    style="margin-left: 8px; color: #388e3c;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif

                        @if (request('kategori'))
                            @php $kategori = \App\Models\Kategori::find(request('kategori')) @endphp
                            @if ($kategori)
                                <span
                                    style="background: #fff3e0; color: #f57c00; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: flex; align-items: center;">
                                    <i class="fas fa-bookmark" style="margin-right: 5px;"></i>
                                    {{ $kategori->nama }}
                                    <a href="{{ route('buku.index', request()->except('kategori')) }}"
                                        style="margin-left: 8px; color: #f57c00;">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                        @endif

                        @if (request('rating'))
                            <span
                                style="background: #fce4ec; color: #c2185b; padding: 5px 12px; border-radius: 20px; font-size: 12px; display: flex; align-items: center;">
                                <i class="fas fa-star" style="margin-right: 5px;"></i>
                                {{ request('rating') }}+ Bintang
                                <a href="{{ route('buku.index', request()->except('rating')) }}"
                                    style="margin-left: 8px; color: #c2185b;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Results Info -->
    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 30px; padding: 0 20px;">
        <div>
            <h3 style="color: #2c3e50; margin-bottom: 5px;">
                @if (request('search'))
                    Hasil pencarian "{{ request('search') }}"
                @elseif(request('jenis') || request('kategori'))
                    Buku {{ request('jenis') ? ucfirst(str_replace('_', ' ', request('jenis'))) : '' }}
                    @if (request('kategori') && $kategori)
                        - {{ $kategori->nama }}
                    @endif
                @else
                    Semua Buku
                @endif
            </h3>
            <p style="color: #546e7a; margin: 0;">
                Ditemukan {{ number_format($bukus->total()) }} buku
                @if ($bukus->hasPages())
                    (Halaman {{ $bukus->currentPage() }} dari {{ $bukus->lastPage() }})
                @endif
            </p>
        </div>

        <!-- View Toggle -->
        <div style="display: flex; gap: 10px;">
            <button onclick="toggleView('grid')" id="gridBtn" class="btn btn-sm btn-outline"
                style="padding: 8px 12px;">
                <i class="fas fa-th"></i> Grid
            </button>
            <button onclick="toggleView('list')" id="listBtn" class="btn btn-sm btn-outline"
                style="padding: 8px 12px;">
                <i class="fas fa-list"></i> List
            </button>
        </div>
    </div>

    <!-- Books Grid/List -->
    <div id="booksContainer">
        @if ($bukus->count() > 0)
            <div class="book-grid" id="booksGrid">
                @foreach ($bukus as $buku)
                    <div class="book-card" data-book-id="{{ $buku->id }}">
                        <div class="book-cover">
                            @if ($buku->cover_gambar)
                                <img src="{{ Storage::url($buku->cover_gambar) }}" alt="{{ $buku->judul }}">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif

                            <!-- Book Type Badge -->
                            <div
                                style="position: absolute; top: 10px; left: 10px; background: {{ $buku->jenis == 'fiksi' ? 'linear-gradient(135deg, #e91e63 0%, #ad1457 100%)' : 'linear-gradient(135deg, #2196f3 0%, #1565c0 100%)' }}; color: white; padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: bold;">
                                <i class="fas fa-{{ $buku->jenis == 'fiksi' ? 'dragon' : 'graduation-cap' }}"></i>
                                {{ $buku->jenis == 'fiksi' ? 'Fiksi' : 'Non-Fiksi' }}
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

                            <p style="color: #546e7a; font-size: 14px; line-height: 1.5; margin: 12px 0;">
                                {{ Str::limit($buku->deskripsi, 100) }}
                            </p>

                            <div class="book-actions">
                                <a href="{{ route('buku.show', $buku) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                @auth
                                    @php
                                        $riwayat = \App\Models\RiwayatBacaan::where('user_id', auth()->id())
                                            ->where('buku_id', $buku->id)
                                            ->first();
                                    @endphp
                                    @if ($riwayat)
                                        <a href="{{ route('buku.baca', $buku) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-bookmark"></i> Lanjutkan
                                        </a>
                                    @else
                                        <a href="{{ route('buku.baca', $buku) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-book-open"></i> Baca
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-sign-in-alt"></i> Login untuk Baca
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 80px 20px; color: #666;">
                <div style="font-size: 80px; margin-bottom: 20px;">
                    <i class="fas fa-search"></i>
                </div>
                <h3 style="color: #2c3e50; margin-bottom: 15px;">Tidak Ada Buku Ditemukan</h3>
                <p style="color: #546e7a; margin-bottom: 30px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    @if (request('search'))
                        Tidak ada buku yang sesuai dengan pencarian "{{ request('search') }}". Coba kata kunci yang berbeda
                        atau kurangi filter.
                    @else
                        Tidak ada buku yang sesuai dengan filter yang dipilih. Coba ubah kriteria filter.
                    @endif
                </p>

                <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                    @if (request('search'))
                        <a href="{{ route('buku.index', request()->except('search')) }}" class="btn btn-primary">
                            <i class="fas fa-undo"></i> Hapus Pencarian
                        </a>
                    @endif

                    <a href="{{ route('buku.index') }}" class="btn btn-outline">
                        <i class="fas fa-list"></i> Lihat Semua Buku
                    </a>

                    <a href="{{ route('pilih.jenis') }}" class="btn btn-success">
                        <i class="fas fa-magic"></i> Coba Rekomendasi
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if ($bukus->hasPages())
        <div style="margin-top: 40px;">
            {{ $bukus->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Quick Recommendation -->
    @auth
        <div class="card"
            style="margin-top: 40px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: none;">
            <div style="text-align: center; padding: 40px 20px;">
                <h3 style="color: #1976d2; margin-bottom: 15px;">
                    <i class="fas fa-magic"></i> Butuh Rekomendasi Personal?
                </h3>
                <p style="color: #546e7a; margin-bottom: 25px;">
                    Dapatkan saran buku yang disesuaikan dengan preferensi Anda menggunakan sistem AI kami
                </p>
                <a href="{{ route('pilih.jenis') }}" class="btn btn-primary">
                    <i class="fas fa-robot"></i> Dapatkan Rekomendasi Sekarang
                </a>
            </div>
        </div>
    @endauth

    <style>
        .book-grid.list-view {
            display: block;
        }

        .book-grid.list-view .book-card {
            display: flex;
            margin-bottom: 20px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(66, 165, 245, 0.1);
        }

        .book-grid.list-view .book-cover {
            width: 120px;
            height: 160px;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .book-grid.list-view .book-info {
            flex: 1;
            padding: 0;
        }

        .book-grid.list-view .book-title {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .book-grid.list-view .book-meta {
            margin: 15px 0;
        }

        .book-grid.list-view .book-actions {
            margin-top: 20px;
        }

        .btn.active {
            background: #42a5f5 !important;
            color: white !important;
            border-color: #42a5f5 !important;
        }
    </style>

    <script>
        // Apply filters function
        function applyFilter() {
            const form = document.getElementById('searchForm');
            const kategori = document.querySelector('select[name="kategori"]').value;
            const sort = document.querySelector('select[name="sort"]').value;
            const rating = document.querySelector('select[name="rating"]').value;

            // Update hidden inputs
            form.querySelector('input[name="kategori"]')?.remove();
            form.querySelector('input[name="rating"]')?.remove();

            if (kategori) {
                const kategoriInput = document.createElement('input');
                kategoriInput.type = 'hidden';
                kategoriInput.name = 'kategori';
                kategoriInput.value = kategori;
                form.appendChild(kategoriInput);
            }

            if (rating) {
                const ratingInput = document.createElement('input');
                ratingInput.type = 'hidden';
                ratingInput.name = 'rating';
                ratingInput.value = rating;
                form.appendChild(ratingInput);
            }

            // Update sort hidden input
            form.querySelector('input[name="sort"]').value = sort;

            form.submit();
        }

        // Toggle view between grid and list
        function toggleView(view) {
            const grid = document.getElementById('booksGrid');
            const gridBtn = document.getElementById('gridBtn');
            const listBtn = document.getElementById('listBtn');

            if (view === 'list') {
                grid.classList.add('list-view');
                listBtn.classList.add('active');
                gridBtn.classList.remove('active');
                localStorage.setItem('booksView', 'list');
            } else {
                grid.classList.remove('list-view');
                gridBtn.classList.add('active');
                listBtn.classList.remove('active');
                localStorage.setItem('booksView', 'grid');
            }
        }

        // Load saved view preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('booksView') || 'grid';
            toggleView(savedView);

            // Auto-submit search form with debouncing
            let searchTimeout;
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (this.value.length >= 3 || this.value.length === 0) {
                            document.getElementById('searchForm').submit();
                        }
                    }, 800);
                });
            }

            // Add animations
            const bookCards = document.querySelectorAll('.book-card');
            bookCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Infinite scroll (optional)
        let isLoading = false;
        window.addEventListener('scroll', function() {
            if (isLoading) return;

            const scrollTop = window.pageYOffset;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;

            if (scrollTop + windowHeight >= documentHeight - 200) {
                // Load more functionality can be implemented here
                // loadMoreBooks();
            }
        });

        // Quick filter buttons
        function quickFilter(type, value) {
            const url = new URL(window.location);
            url.searchParams.set(type, value);
            window.location.href = url.toString();
        }
    </script>
@endsection
