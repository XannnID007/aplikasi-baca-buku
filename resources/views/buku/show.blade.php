@extends('layouts.app')

@section('title', $buku->judul)

@section('content')
    <!-- Book Detail Hero -->
    <div
        style="background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%); padding: 40px 20px; border-radius: 20px; margin-bottom: 30px;">
        <div
            style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 300px 1fr; gap: 40px; align-items: start;">
            <!-- Book Cover -->
            <div>
                <div style="position: relative;">
                    <div
                        style="width: 100%; height: 400px; border-radius: 15px; overflow: hidden; box-shadow: 0 15px 35px rgba(66, 165, 245, 0.3); background: #e3f2fd;">
                        @if ($buku->cover_gambar)
                            <img src="{{ Storage::url($buku->cover_gambar) }}" alt="{{ $buku->judul }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div
                                style="display: flex; align-items: center; justify-content: center; height: 100%; font-size: 64px; color: #42a5f5;">
                                <i class="fas fa-book"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Book Type Badge -->
                    <div
                        style="position: absolute; top: 15px; left: 15px; background: {{ $buku->jenis == 'fiksi' ? 'linear-gradient(135deg, #e91e63 0%, #ad1457 100%)' : 'linear-gradient(135deg, #2196f3 0%, #1565c0 100%)' }}; color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: bold;">
                        <i class="fas fa-{{ $buku->jenis == 'fiksi' ? 'dragon' : 'graduation-cap' }}"></i>
                        {{ $buku->jenis == 'fiksi' ? 'Fiksi' : 'Non-Fiksi' }}
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 12px;">
                    @auth
                        <!-- Continue Reading / Start Reading -->
                        @if ($riwayatBacaan)
                            <a href="{{ route('buku.baca', $buku) }}" class="btn btn-primary"
                                style="padding: 15px; font-size: 16px; justify-content: center;">
                                <i class="fas fa-bookmark"></i> Lanjutkan Bacaan (Hal. {{ $riwayatBacaan->halaman_terakhir }})
                            </a>
                            <div
                                style="background: #e3f2fd; padding: 12px; border-radius: 8px; text-align: center; font-size: 14px; color: #1976d2;">
                                <i class="fas fa-clock"></i> Terakhir dibaca
                                {{ $riwayatBacaan->terakhir_dibaca->diffForHumans() }}
                            </div>
                        @else
                            <a href="{{ route('buku.baca', $buku) }}" class="btn btn-success"
                                style="padding: 15px; font-size: 16px; justify-content: center;">
                                <i class="fas fa-book-open"></i> Mulai Membaca
                            </a>
                        @endif

                        <!-- Bookmark Button -->
                        <button onclick="toggleBookmark({{ $buku->id }})"
                            class="btn {{ $userBookmark ? 'btn-warning' : 'btn-outline' }}" id="bookmarkBtn"
                            style="padding: 12px; font-size: 14px; justify-content: center;">
                            <i class="{{ $userBookmark ? 'fas' : 'far' }} fa-bookmark"></i>
                            <span id="bookmarkText">{{ $userBookmark ? 'Hapus Bookmark' : 'Tambah Bookmark' }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary"
                            style="padding: 15px; font-size: 16px; justify-content: center;">
                            <i class="fas fa-sign-in-alt"></i> Login untuk Membaca
                        </a>
                    @endauth

                    <!-- Download Button -->
                    <a href="{{ route('download.buku', $buku) }}" class="btn btn-outline"
                        style="padding: 12px; font-size: 14px; justify-content: center;">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>

            <!-- Book Info -->
            <div>
                <div style="margin-bottom: 20px;">
                    <h1 style="color: #2c3e50; font-size: 36px; margin-bottom: 10px; line-height: 1.2;">{{ $buku->judul }}
                    </h1>
                    <p style="color: #546e7a; font-size: 20px; margin-bottom: 15px;">
                        <i class="fas fa-user"></i> {{ $buku->penulis }}
                    </p>
                </div>

                <!-- Rating and Stats -->
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 20px; margin-bottom: 25px;">
                    <div
                        style="text-align: center; padding: 15px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(66, 165, 245, 0.1);">
                        <div style="font-size: 24px; color: #ffc107; margin-bottom: 5px;">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($buku->rating_rata_rata))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $buku->rating_rata_rata)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div style="font-weight: bold; color: #2c3e50;">{{ number_format($buku->rating_rata_rata, 1) }}
                        </div>
                        <div style="font-size: 12px; color: #546e7a;">{{ $buku->total_ratings }} rating</div>
                    </div>

                    <div
                        style="text-align: center; padding: 15px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(66, 165, 245, 0.1);">
                        <div style="font-size: 24px; color: #42a5f5; margin-bottom: 5px;">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div style="font-weight: bold; color: #2c3e50;">{{ number_format($buku->views) }}</div>
                        <div style="font-size: 12px; color: #546e7a;">views</div>
                    </div>

                    <div
                        style="text-align: center; padding: 15px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(66, 165, 245, 0.1);">
                        <div style="font-size: 24px; color: #4caf50; margin-bottom: 5px;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div style="font-weight: bold; color: #2c3e50;">{{ number_format($buku->halaman) }}</div>
                        <div style="font-size: 12px; color: #546e7a;">halaman</div>
                    </div>

                    <div
                        style="text-align: center; padding: 15px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(66, 165, 245, 0.1);">
                        <div style="font-size: 24px; color: #ff9800; margin-bottom: 5px;">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div style="font-weight: bold; color: #2c3e50;">{{ $buku->tahun_terbit }}</div>
                        <div style="font-size: 12px; color: #546e7a;">tahun</div>
                    </div>
                </div>

                <!-- Categories -->
                <div style="margin-bottom: 25px;">
                    <h6
                        style="color: #546e7a; margin-bottom: 10px; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">
                        Kategori</h6>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @foreach ($buku->kategoris as $kategori)
                            <a href="{{ route('buku.index', ['kategori' => $kategori->id]) }}" class="category-tag"
                                style="text-decoration: none;">
                                {{ $kategori->nama }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <h6
                        style="color: #546e7a; margin-bottom: 15px; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">
                        Deskripsi</h6>
                    <div style="color: #2c3e50; line-height: 1.8; font-size: 16px;">
                        {{ $buku->deskripsi }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Section -->
    @auth
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star"></i> Berikan Rating
                </h3>
                <p class="card-subtitle">Bagaimana pendapat Anda tentang buku ini?</p>
            </div>

            <div style="padding: 30px;">
                @if ($userRating)
                    <div style="background: #e8f5e8; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                        <h6 style="color: #388e3c; margin-bottom: 10px;">
                            <i class="fas fa-check-circle"></i> Rating Anda
                        </h6>
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                            <div style="margin-right: 15px;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"
                                        style="color: {{ $i <= $userRating->rating ? '#ffc107' : '#e0e0e0' }}; font-size: 20px;"></i>
                                @endfor
                            </div>
                            <span style="color: #388e3c; font-weight: 600;">{{ $userRating->rating }}/5</span>
                        </div>
                        @if ($userRating->review)
                            <p style="color: #2c3e50; margin: 0; font-style: italic;">"{{ $userRating->review }}"</p>
                        @endif
                        <button onclick="editRating()" class="btn btn-sm btn-outline" style="margin-top: 10px;">
                            <i class="fas fa-edit"></i> Edit Rating
                        </button>
                    </div>
                @endif

                <form id="ratingForm" style="{{ $userRating ? 'display: none;' : '' }}">
                    @csrf
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; color: #2c3e50; font-weight: 600;">Rating *</label>
                        <div class="rating-stars" id="ratingStars">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="far fa-star" data-rating="{{ $i }}"
                                    onclick="setRating({{ $i }})"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="selectedRating" value="{{ $userRating->rating ?? '' }}">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; color: #2c3e50; font-weight: 600;">Review
                            (opsional)</label>
                        <textarea name="review" class="form-control" rows="4" placeholder="Tulis review Anda tentang buku ini...">{{ $userRating->review ?? '' }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitRatingBtn">
                        <i class="fas fa-paper-plane"></i> {{ $userRating ? 'Update Rating' : 'Submit Rating' }}
                    </button>

                    @if ($userRating)
                        <button type="button" onclick="cancelEdit()" class="btn btn-outline" style="margin-left: 10px;">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    @endif
                </form>
            </div>
        </div>
    @endauth

    <!-- Reviews Section -->
    @if ($buku->ratings->count() > 0)
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-comments"></i> Review Pembaca
                </h3>
                <p class="card-subtitle">{{ $buku->ratings->count() }} review dari pembaca lain</p>
            </div>

            <div style="padding: 30px;">
                @foreach ($buku->ratings->take(5) as $rating)
                    <div style="border-bottom: 1px solid #e3f2fd; padding-bottom: 20px; margin-bottom: 20px;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <div>
                                <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                    <div
                                        style="width: 40px; height: 40px; background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 12px;">
                                        {{ substr($rating->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 style="color: #2c3e50; margin: 0; font-size: 16px;">{{ $rating->user->name }}
                                        </h6>
                                        <div style="color: #546e7a; font-size: 12px;">
                                            {{ $rating->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star"
                                        style="color: {{ $i <= $rating->rating ? '#ffc107' : '#e0e0e0' }}; font-size: 14px;"></i>
                                @endfor
                                <span
                                    style="margin-left: 8px; color: #546e7a; font-size: 14px;">{{ $rating->rating }}/5</span>
                            </div>
                        </div>

                        @if ($rating->review)
                            <p style="color: #2c3e50; line-height: 1.6; margin: 0; font-style: italic;">
                                "{{ $rating->review }}"
                            </p>
                        @endif
                    </div>
                @endforeach

                @if ($buku->ratings->count() > 5)
                    <div style="text-align: center; margin-top: 20px;">
                        <button onclick="loadMoreReviews()" class="btn btn-outline" id="loadMoreBtn">
                            <i class="fas fa-chevron-down"></i> Lihat {{ $buku->ratings->count() - 5 }} Review Lainnya
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Similar Books -->
    @php
        $similarBooks = \App\Models\Buku::whereHas('kategoris', function ($query) use ($buku) {
            $query->whereIn('kategori_id', $buku->kategoris->pluck('id'));
        })
            ->where('id', '!=', $buku->id)
            ->take(4)
            ->get();
    @endphp

    @if ($similarBooks->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book-open"></i> Buku Serupa
                </h3>
                <p class="card-subtitle">Buku lain yang mungkin Anda sukai</p>
            </div>

            <div style="padding: 30px;">
                <div class="book-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                    @foreach ($similarBooks as $similarBook)
                        <div class="book-card" data-book-id="{{ $similarBook->id }}">
                            <div class="book-cover" style="height: 180px;">
                                @if ($similarBook->cover_gambar)
                                    <img src="{{ Storage::url($similarBook->cover_gambar) }}"
                                        alt="{{ $similarBook->judul }}">
                                @else
                                    <div class="placeholder">
                                        <i class="fas fa-book"></i>
                                    </div>
                                @endif

                                @auth
                                    <div style="position: absolute; top: 10px; right: 10px;">
                                        <button onclick="toggleBookmark({{ $similarBook->id }})"
                                            class="bookmark-btn {{ \App\Models\Bookmark::where('user_id', auth()->id())->where('buku_id', $similarBook->id)->exists()? 'bookmarked': '' }}"
                                            style="background: rgba(255,255,255,0.9); border: none; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="{{ \App\Models\Bookmark::where('user_id', auth()->id())->where('buku_id', $similarBook->id)->exists()? 'fas': 'far' }} fa-bookmark"
                                                style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                @endauth
                            </div>

                            <div class="book-info">
                                <h4 class="book-title" style="font-size: 16px;">{{ Str::limit($similarBook->judul, 40) }}
                                </h4>
                                <p class="book-author" style="font-size: 14px;">{{ $similarBook->penulis }}</p>

                                <div class="book-rating" style="margin: 10px 0;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= floor($similarBook->rating_rata_rata))
                                            <i class="fas fa-star" style="font-size: 12px;"></i>
                                        @elseif($i - 0.5 <= $similarBook->rating_rata_rata)
                                            <i class="fas fa-star-half-alt" style="font-size: 12px;"></i>
                                        @else
                                            <i class="far fa-star" style="font-size: 12px;"></i>
                                        @endif
                                    @endfor
                                    <span style="margin-left: 5px; color: #546e7a; font-size: 12px;">
                                        ({{ number_format($similarBook->rating_rata_rata, 1) }})
                                    </span>
                                </div>

                                <div class="book-actions">
                                    <a href="{{ route('buku.show', $similarBook) }}" class="btn btn-primary btn-sm"
                                        style="font-size: 12px;">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                    @auth
                                        <a href="{{ route('buku.baca', $similarBook) }}" class="btn btn-success btn-sm"
                                            style="font-size: 12px;">
                                            <i class="fas fa-book-open"></i> Baca
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <script>
        let currentRating = {{ $userRating->rating ?? 0 }};

        // Rating functionality
        function setRating(rating) {
            currentRating = rating;
            document.getElementById('selectedRating').value = rating;

            const stars = document.querySelectorAll('#ratingStars i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.className = 'fas fa-star active';
                } else {
                    star.className = 'far fa-star';
                }
            });
        }

        // Initialize rating stars
        document.addEventListener('DOMContentLoaded', function() {
            if (currentRating > 0) {
                setRating(currentRating);
            }

            // Rating form submission
            document.getElementById('ratingForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const rating = document.getElementById('selectedRating').value;
                const review = document.querySelector('textarea[name="review"]').value;

                if (!rating) {
                    alert('Silakan pilih rating terlebih dahulu');
                    return;
                }

                const submitBtn = document.getElementById('submitRatingBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitBtn.disabled = true;

                fetch(`/buku/{{ $buku->id }}/rating`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            rating: parseInt(rating),
                            review: review
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        showAlert('success', data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    })
                    .catch(error => {
                        showAlert('error', 'Terjadi kesalahan saat menyimpan rating');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });
        });

        function editRating() {
            document.getElementById('ratingForm').style.display = 'block';
            document.querySelector('.card .alert-success')?.style.display = 'none';
        }

        function cancelEdit() {
            document.getElementById('ratingForm').style.display = 'none';
            document.querySelector('.card .alert-success')?.style.display = 'block';
        }

        // Bookmark functionality
        function toggleBookmark(bookId) {
            fetch(`/buku/${bookId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const bookmarkBtn = document.getElementById('bookmarkBtn');
                    const bookmarkText = document.getElementById('bookmarkText');
                    const icon = bookmarkBtn.querySelector('i');

                    if (data.bookmarked) {
                        bookmarkBtn.className = 'btn btn-warning';
                        icon.className = 'fas fa-bookmark';
                        bookmarkText.textContent = 'Hapus Bookmark';
                    } else {
                        bookmarkBtn.className = 'btn btn-outline';
                        icon.className = 'far fa-bookmark';
                        bookmarkText.textContent = 'Tambah Bookmark';
                    }

                    showAlert('success', data.message);
                })
                .catch(error => {
                    showAlert('error', 'Terjadi kesalahan');
                });
        }

        // Load more reviews
        function loadMoreReviews() {
            const btn = document.getElementById('loadMoreBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';

            fetch(`/buku/{{ $buku->id }}/reviews`)
                .then(response => response.json())
                .then(data => {
                    // Implementation for loading more reviews
                    // This would require additional backend endpoint
                })
                .catch(error => {
                    btn.innerHTML = '<i class="fas fa-chevron-down"></i> Lihat Review Lainnya';
                });
        }

        // Scroll animations
        function animateOnScroll() {
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

            document.querySelectorAll('.card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                observer.observe(card);
            });
        }

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            animateOnScroll();

            // Track book view for analytics
            fetch(`/api/buku/{{ $buku->id }}/view`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                }
            });
        });

        // Responsive adjustments
        function adjustForMobile() {
            if (window.innerWidth <= 768) {
                const heroGrid = document.querySelector('.hero > div > div');
                if (heroGrid) {
                    heroGrid.style.gridTemplateColumns = '1fr';
                    heroGrid.style.gap = '30px';
                    heroGrid.style.textAlign = 'center';
                }
            }
        }

        window.addEventListener('resize', adjustForMobile);
        adjustForMobile();
    </script>

    @push('styles')
        <style>
            @media (max-width: 768px) {
                .hero>div>div {
                    grid-template-columns: 1fr !important;
                    gap: 30px !important;
                    text-align: center !important;
                }

                .hero>div>div>div:first-child {
                    max-width: 250px;
                    margin: 0 auto;
                }
            }

            .rating-stars i {
                font-size: 24px;
                color: #e0e0e0;
                cursor: pointer;
                margin-right: 5px;
                transition: color 0.3s;
            }

            .rating-stars i:hover,
            .rating-stars i.active {
                color: #ffc107;
            }

            .book-grid {
                gap: 20px;
            }

            .book-card {
                transition: transform 0.3s ease;
            }

            .book-card:hover {
                transform: translateY(-5px);
            }
        </style>
    @endpush
@endsection
