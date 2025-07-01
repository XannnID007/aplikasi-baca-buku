@extends('layouts.app')

@section('title', 'Rekomendasi Buku Untuk Anda')

@section('content')
    <div class="hero">
        <h1><i class="fas fa-magic"></i> Rekomendasi Khusus Untuk Anda</h1>
        <p>Berdasarkan preferensi yang Anda pilih, kami telah menganalisis menggunakan algoritma K-means clustering untuk
            memberikan rekomendasi buku terbaik</p>

        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 30px; flex-wrap: wrap;">
            <div
                style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 15px 25px; border-radius: 25px;">
                <i class="fas fa-robot" style="color: #1976d2; margin-right: 8px;"></i>
                <strong style="color: #1976d2;">Algoritma: K-means Clustering</strong>
            </div>
            <div
                style="background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%); padding: 15px 25px; border-radius: 25px;">
                <i class="fas fa-chart-line" style="color: #388e3c; margin-right: 8px;"></i>
                <strong style="color: #388e3c;">{{ $rekomendasi->count() }} Buku Ditemukan</strong>
            </div>
            <div
                style="background: linear-gradient(135deg, #fff3e0 0%, #ffcc02 30%); padding: 15px 25px; border-radius: 25px;">
                <i class="fas fa-star" style="color: #f57c00; margin-right: 8px;"></i>
                <strong style="color: #f57c00;">Rating Rata-rata:
                    {{ number_format($rekomendasi->avg('rating_rata_rata'), 1) }}</strong>
            </div>
        </div>
    </div>

    @if ($rekomendasi->count() > 0)
        <!-- Filter dan Sort -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div>
                    <h3 style="color: #2c3e50; margin-bottom: 5px;">
                        <i class="fas fa-filter"></i> Filter & Urutkan
                    </h3>
                    <p style="color: #546e7a; font-size: 14px;">Sesuaikan hasil rekomendasi sesuai keinginan Anda</p>
                </div>

                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <select id="sortBy" class="form-control" style="width: auto; min-width: 150px;">
                        <option value="rating">Rating Tertinggi</option>
                        <option value="views">Paling Populer</option>
                        <option value="newest">Terbaru</option>
                        <option value="pages">Halaman Terpendek</option>
                    </select>

                    <select id="filterRating" class="form-control" style="width: auto; min-width: 120px;">
                        <option value="">Semua Rating</option>
                        <option value="4">Rating 4+</option>
                        <option value="3">Rating 3+</option>
                    </select>

                    <button onclick="resetFilter()" class="btn btn-outline" style="padding: 12px 20px;">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Hasil Rekomendasi -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-books"></i> Buku yang Direkomendasikan
                </h2>
                <p class="card-subtitle">Dipilih khusus berdasarkan analisis preferensi dan clustering algoritma K-means</p>
            </div>

            <div class="book-grid" id="bookGrid">
                @foreach ($rekomendasi as $buku)
                    <div class="book-card" data-rating="{{ $buku->rating_rata_rata }}" data-views="{{ $buku->views }}"
                        data-date="{{ $buku->created_at->timestamp }}" data-pages="{{ $buku->halaman }}">

                        <div class="book-cover">
                            @if ($buku->cover_gambar)
                                <img src="{{ Storage::url($buku->cover_gambar) }}" alt="{{ $buku->judul }}">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif

                            <!-- Recommendation Badge -->
                            <div
                                style="position: absolute; top: 10px; left: 10px; background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%); color: white; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                                <i class="fas fa-magic"></i> Rekomendasi
                            </div>

                            <!-- Quick Actions -->
                            <div style="position: absolute; top: 10px; right: 10px; display: flex; gap: 5px; opacity: 0; transition: opacity 0.3s;"
                                class="quick-actions">
                                @auth
                                    <button onclick="toggleBookmark({{ $buku->id }})" class="bookmark-btn"
                                        style="background: rgba(255,255,255,0.9); border: none; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="far fa-bookmark"></i>
                                    </button>
                                @endauth
                            </div>
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
                                    ({{ number_format($buku->rating_rata_rata, 1) }})
                                </span>
                            </div>

                            <div class="book-categories">
                                @foreach ($buku->kategoris as $kategori)
                                    <span class="category-tag">{{ $kategori->nama }}</span>
                                @endforeach
                            </div>

                            <p style="color: #546e7a; font-size: 14px; line-height: 1.5; margin: 12px 0;">
                                {{ Str::limit($buku->deskripsi, 120) }}
                            </p>

                            <div class="book-actions">
                                <a href="{{ route('buku.show', $buku) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>

                                @auth
                                    <a href="{{ route('buku.baca', $buku) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-book-open"></i> Baca
                                    </a>
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
        </div>

        <!-- Clustering Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Bagaimana Sistem Rekomendasi Bekerja?
                </h3>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 48px; color: #42a5f5; margin-bottom: 15px;">
                        <i class="fas fa-database"></i>
                    </div>
                    <h4 style="color: #2c3e50; margin-bottom: 10px;">Analisis Data</h4>
                    <p style="color: #546e7a; line-height: 1.6;">
                        Sistem menganalisis data buku berdasarkan views, halaman, tahun terbit, rating, dan deskripsi untuk
                        mengidentifikasi pola.
                    </p>
                </div>

                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 48px; color: #66bb6a; margin-bottom: 15px;">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h4 style="color: #2c3e50; margin-bottom: 10px;">K-means Clustering</h4>
                    <p style="color: #546e7a; line-height: 1.6;">
                        Algoritma K-means mengelompokkan buku ke dalam cluster berdasarkan kesamaan karakteristik dan
                        preferensi Anda.
                    </p>
                </div>

                <div style="text-align: center; padding: 20px;">
                    <div style="font-size: 48px; color: #ff7043; margin-bottom: 15px;">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h4 style="color: #2c3e50; margin-bottom: 10px;">Hasil Personal</h4>
                    <p style="color: #546e7a; line-height: 1.6;">
                        Cluster terbaik dipilih berdasarkan history bacaan Anda untuk memberikan rekomendasi yang paling
                        relevan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="card"
            style="text-align: center; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: none;">
            <h3 style="color: #1976d2; margin-bottom: 15px;">
                <i class="fas fa-heart"></i> Suka dengan rekomendasi ini?
            </h3>
            <p style="color: #546e7a; margin-bottom: 25px;">
                Mulai baca buku dan berikan rating untuk mendapatkan rekomendasi yang lebih akurat!
            </p>

            <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('pilih.jenis') }}" class="btn btn-outline">
                    <i class="fas fa-redo"></i> Coba Preferensi Lain
                </a>

                <a href="{{ route('buku.index') }}" class="btn btn-primary">
                    <i class="fas fa-books"></i> Jelajahi Semua Buku
                </a>

                @guest
                    <a href="{{ route('register') }}" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </a>
                @endguest
            </div>
        </div>
    @else
        <!-- No Results -->
        <div class="card" style="text-align: center; padding: 60px 30px;">
            <div style="font-size: 80px; color: #bbb; margin-bottom: 20px;">
                <i class="fas fa-search"></i>
            </div>
            <h3 style="color: #2c3e50; margin-bottom: 15px;">Tidak Ada Rekomendasi Ditemukan</h3>
            <p style="color: #546e7a; margin-bottom: 30px; max-width: 500px; margin-left: auto; margin-right: auto;">
                Maaf, kami tidak dapat menemukan buku yang sesuai dengan preferensi Anda. Coba pilih kategori lain atau
                jelajahi katalog lengkap kami.
            </p>

            <div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
                <a href="{{ route('pilih.jenis') }}" class="btn btn-primary">
                    <i class="fas fa-redo"></i> Pilih Ulang Preferensi
                </a>

                <a href="{{ route('buku.index') }}" class="btn btn-outline">
                    <i class="fas fa-books"></i> Lihat Semua Buku
                </a>
            </div>
        </div>
    @endif

    <style>
        .book-card:hover .quick-actions {
            opacity: 1;
        }

        .book-card {
            position: relative;
        }

        .book-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(66, 165, 245, 0.05) 0%, rgba(25, 118, 210, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 15px;
            z-index: 1;
        }

        .book-card:hover::before {
            opacity: 1;
        }

        .book-card>* {
            position: relative;
            z-index: 2;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            display: none;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e3f2fd;
            border-top: 4px solid #42a5f5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // Filter and Sort functionality
        function filterAndSort() {
            const sortBy = document.getElementById('sortBy').value;
            const filterRating = document.getElementById('filterRating').value;
            const bookGrid = document.getElementById('bookGrid');
            const books = Array.from(bookGrid.children);

            // Show loading
            showLoading();

            // Filter books
            const filteredBooks = books.filter(book => {
                if (!filterRating) return true;
                const rating = parseFloat(book.dataset.rating);
                return rating >= parseFloat(filterRating);
            });

            // Sort books
            filteredBooks.sort((a, b) => {
                switch (sortBy) {
                    case 'rating':
                        return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                    case 'views':
                        return parseInt(b.dataset.views) - parseInt(a.dataset.views);
                    case 'newest':
                        return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                    case 'pages':
                        return parseInt(a.dataset.pages) - parseInt(b.dataset.pages);
                    default:
                        return 0;
                }
            });

            // Hide all books first
            books.forEach(book => book.style.display = 'none');

            // Show filtered and sorted books with animation
            setTimeout(() => {
                filteredBooks.forEach((book, index) => {
                    book.style.display = 'block';
                    book.classList.add('fade-in');
                    book.style.animationDelay = `${index * 0.1}s`;
                });

                hideLoading();

                // Show message if no books found
                if (filteredBooks.length === 0) {
                    showNoResultsMessage();
                } else {
                    hideNoResultsMessage();
                }
            }, 300);
        }

        function resetFilter() {
            document.getElementById('sortBy').value = 'rating';
            document.getElementById('filterRating').value = '';
            filterAndSort();
        }

        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        function showNoResultsMessage() {
            const bookGrid = document.getElementById('bookGrid');
            const existingMessage = document.getElementById('noResultsMessage');

            if (!existingMessage) {
                const message = document.createElement('div');
                message.id = 'noResultsMessage';
                message.className = 'col-span-full text-center py-12';
                message.innerHTML = `
            <div style="font-size: 64px; color: #bbb; margin-bottom: 20px;">
                <i class="fas fa-filter"></i>
            </div>
            <h3 style="color: #2c3e50; margin-bottom: 10px;">Tidak Ada Buku yang Sesuai Filter</h3>
            <p style="color: #546e7a;">Coba ubah pengaturan filter untuk melihat lebih banyak hasil</p>
        `;
                bookGrid.appendChild(message);
            }
        }

        function hideNoResultsMessage() {
            const message = document.getElementById('noResultsMessage');
            if (message) {
                message.remove();
            }
        }

        // Bookmark functionality
        function toggleBookmark(bookId) {
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                showAlert('warning', 'Silakan login terlebih dahulu untuk menambah bookmark');
                return;
            }

            fetch(`/buku/${bookId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const bookmarkBtn = document.querySelector(`[data-book-id="${bookId}"] .bookmark-btn`);
                    if (bookmarkBtn) {
                        bookmarkBtn.classList.toggle('bookmarked', data.bookmarked);
                        bookmarkBtn.innerHTML = data.bookmarked ? '<i class="fas fa-bookmark"></i>' :
                            '<i class="far fa-bookmark"></i>';
                    }
                    showAlert('success', data.message);
                })
                .catch(error => {
                    showAlert('error', 'Terjadi kesalahan saat menyimpan bookmark');
                });
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listeners for filter and sort
            document.getElementById('sortBy').addEventListener('change', filterAndSort);
            document.getElementById('filterRating').addEventListener('change', filterAndSort);

            // Add animation to books on load
            const books = document.querySelectorAll('.book-card');
            books.forEach((book, index) => {
                book.style.opacity = '0';
                book.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    book.style.transition = 'all 0.6s ease';
                    book.style.opacity = '1';
                    book.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Initialize tooltips (if using Bootstrap or similar)
            initializeTooltips();

            // Track recommendation clicks for analytics
            trackRecommendationInteractions();
        });

        function initializeTooltips() {
            // Add tooltips to rating stars
            const ratingStars = document.querySelectorAll('.book-rating i');
            ratingStars.forEach(star => {
                star.title = 'Rating: ' + star.closest('.book-rating').querySelector('span').textContent;
            });
        }

        function trackRecommendationInteractions() {
            // Track when user clicks on recommended books
            const bookLinks = document.querySelectorAll('.book-card a');
            bookLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Send analytics data (implement your analytics here)
                    console.log('Recommendation clicked:', this.href);
                });
            });
        }

        // Infinite scroll for large datasets (optional enhancement)
        function initializeInfiniteScroll() {
            let loading = false;
            let page = 1;

            window.addEventListener('scroll', function() {
                if (loading) return;

                const scrollTop = window.pageYOffset;
                const windowHeight = window.innerHeight;
                const documentHeight = document.documentElement.scrollHeight;

                if (scrollTop + windowHeight >= documentHeight - 100) {
                    loadMoreRecommendations();
                }
            });
        }

        function loadMoreRecommendations() {
            // Implementation for loading more recommendations
            // This would require backend pagination support
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Press 'R' to reset filters
            if (e.key.toLowerCase() === 'r' && e.ctrlKey) {
                e.preventDefault();
                resetFilter();
            }

            // Press 'S' to focus on sort dropdown
            if (e.key.toLowerCase() === 's' && e.ctrlKey) {
                e.preventDefault();
                document.getElementById('sortBy').focus();
            }
        });

        // Save user preferences for future recommendations
        function saveUserPreferences() {
            const preferences = {
                sortBy: document.getElementById('sortBy').value,
                filterRating: document.getElementById('filterRating').value,
                timestamp: Date.now()
            };

            localStorage.setItem('userRecommendationPreferences', JSON.stringify(preferences));
        }

        function loadUserPreferences() {
            const saved = localStorage.getItem('userRecommendationPreferences');
            if (saved) {
                const preferences = JSON.parse(saved);

                // Only apply if saved within last 7 days
                if (Date.now() - preferences.timestamp < 7 * 24 * 60 * 60 * 1000) {
                    document.getElementById('sortBy').value = preferences.sortBy || 'rating';
                    document.getElementById('filterRating').value = preferences.filterRating || '';
                    filterAndSort();
                }
            }
        }

        // Load preferences on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUserPreferences();

            // Save preferences when changed
            document.getElementById('sortBy').addEventListener('change', saveUserPreferences);
            document.getElementById('filterRating').addEventListener('change', saveUserPreferences);
        });

        // Share recommendation functionality
        function shareRecommendation() {
            const url = window.location.href;
            const text = 'Lihat rekomendasi buku personal yang saya dapatkan dari Perpustakaan Digital!';

            if (navigator.share) {
                navigator.share({
                    title: 'Rekomendasi Buku Personal',
                    text: text,
                    url: url
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(`${text} ${url}`).then(() => {
                    showAlert('success', 'Link rekomendasi telah disalin ke clipboard!');
                });
            }
        }

        // Print recommendation functionality
        function printRecommendations() {
            const printContent = document.querySelector('.book-grid').cloneNode(true);
            const printWindow = window.open('', '_blank');

            printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Rekomendasi Buku Personal</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .book-card { break-inside: avoid; margin-bottom: 20px; border: 1px solid #ddd; padding: 15px; }
                .book-title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
                .book-author { color: #666; margin-bottom: 10px; }
                .category-tag { background: #e3f2fd; padding: 3px 8px; border-radius: 10px; font-size: 12px; margin-right: 5px; }
                @media print { body { margin: 0; } }
            </style>
        </head>
        <body>
            <h1>Rekomendasi Buku Personal</h1>
            <p>Dihasilkan pada: ${new Date().toLocaleDateString('id-ID')}</p>
            ${printContent.innerHTML}
        </body>
        </html>
    `);

            printWindow.document.close();
            printWindow.print();
        }

        // Enhanced error handling
        window.addEventListener('error', function(e) {
            console.error('JavaScript Error:', e.error);
            showAlert('error', 'Terjadi kesalahan. Silakan refresh halaman.');
        });

        // Performance monitoring
        function measurePerformance() {
            if ('performance' in window) {
                window.addEventListener('load', function() {
                    const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
                    console.log('Page load time:', loadTime + 'ms');
                });
            }
        }

        measurePerformance();
    </script>

@endsection
