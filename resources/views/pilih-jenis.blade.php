@extends('layouts.app')

@section('title', 'Pilih Jenis Buku')

@section('content')
    <div class="hero">
        <h1><i class="fas fa-magic"></i> Temukan Rekomendasi Buku Terbaik</h1>
        <p>Mulai perjalanan membaca Anda dengan memilih jenis buku yang Anda sukai. Sistem kami akan memberikan rekomendasi
            terbaik menggunakan algoritma K-means clustering.</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-book-reader"></i> Pilih Jenis Buku Favorit Anda
            </h2>
            <p class="card-subtitle">Pilih salah satu jenis buku yang paling Anda minati</p>
        </div>

        <form action="{{ route('pilih.jenis.proses') }}" method="POST">
            @csrf
            <div class="choice-container">
                <div class="choice-card" onclick="selectChoice('fiksi')">
                    <input type="radio" name="jenis" value="fiksi" id="fiksi" style="display: none;" required>
                    <div class="choice-icon">
                        <i class="fas fa-dragon"></i>
                    </div>
                    <h3 class="choice-title">Buku Fiksi</h3>
                    <p class="choice-description">
                        Jelajahi dunia imajinatif dengan cerita-cerita menarik, novel fantasi, roman, thriller, dan berbagai
                        genre fiksi lainnya yang akan membawa Anda ke petualangan tak terlupakan.
                    </p>
                    <div style="margin-top: 20px; color: #42a5f5; font-weight: 600;">
                        <i class="fas fa-heart"></i> Romantis
                        <i class="fas fa-mask" style="margin-left: 15px;"></i> Horor
                        <i class="fas fa-rocket" style="margin-left: 15px;"></i> Sci-Fi
                    </div>
                </div>

                <div class="choice-card" onclick="selectChoice('non_fiksi')">
                    <input type="radio" name="jenis" value="non_fiksi" id="non_fiksi" style="display: none;" required>
                    <div class="choice-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="choice-title">Buku Non-Fiksi</h3>
                    <p class="choice-description">
                        Tingkatkan pengetahuan dan keterampilan Anda dengan buku-buku edukatif, biografi inspiratif, panduan
                        teknologi, dan berbagai materi pembelajaran yang bermanfaat.
                    </p>
                    <div style="margin-top: 20px; color: #42a5f5; font-weight: 600;">
                        <i class="fas fa-brain"></i> Edukasi
                        <i class="fas fa-laptop-code" style="margin-left: 15px;"></i> Teknologi
                        <i class="fas fa-user" style="margin-left: 15px;"></i> Biografi
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 40px;">
                <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 18px;" id="submitBtn"
                    disabled>
                    <i class="fas fa-arrow-right"></i> Lanjutkan ke Pemilihan Kategori
                </button>
            </div>
        </form>
    </div>

    <!-- Info Section -->
    <div class="card" style="margin-top: 40px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-lightbulb"></i> Mengapa Pilih Berdasarkan Preferensi?
            </h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 48px; color: #42a5f5; margin-bottom: 15px;">
                    <i class="fas fa-robot"></i>
                </div>
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Algoritma Cerdas</h4>
                <p style="color: #546e7a; line-height: 1.6;">
                    Menggunakan K-means clustering untuk menganalisis pola bacaan dan memberikan rekomendasi yang akurat
                    berdasarkan preferensi Anda.
                </p>
            </div>

            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 48px; color: #66bb6a; margin-bottom: 15px;">
                    <i class="fas fa-target"></i>
                </div>
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Rekomendasi Personal</h4>
                <p style="color: #546e7a; line-height: 1.6;">
                    Setiap rekomendasi disesuaikan dengan selera dan history bacaan Anda untuk pengalaman membaca yang lebih
                    memuaskan.
                </p>
            </div>

            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 48px; color: #ff7043; margin-bottom: 15px;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Terus Belajar</h4>
                <p style="color: #546e7a; line-height: 1.6;">
                    Semakin sering Anda membaca dan memberikan rating, sistem akan semakin pintar dalam memberikan
                    rekomendasi.
                </p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="card" style="margin-top: 40px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar"></i> Statistik Koleksi Buku
            </h3>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div
                style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 25px; border-radius: 15px; text-align: center;">
                <div style="font-size: 36px; color: #1976d2; font-weight: bold; margin-bottom: 10px;">
                    {{ \App\Models\Buku::where('jenis', 'fiksi')->count() }}
                </div>
                <div style="color: #546e7a; font-weight: 500;">Buku Fiksi</div>
            </div>

            <div
                style="background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%); padding: 25px; border-radius: 15px; text-align: center;">
                <div style="font-size: 36px; color: #388e3c; font-weight: bold; margin-bottom: 10px;">
                    {{ \App\Models\Buku::where('jenis', 'non_fiksi')->count() }}
                </div>
                <div style="color: #546e7a; font-weight: 500;">Buku Non-Fiksi</div>
            </div>

            <div
                style="background: linear-gradient(135deg, #fff3e0 0%, #ffcc02 30%); padding: 25px; border-radius: 15px; text-align: center;">
                <div style="font-size: 36px; color: #f57c00; font-weight: bold; margin-bottom: 10px;">
                    {{ \App\Models\Kategori::count() }}
                </div>
                <div style="color: #546e7a; font-weight: 500;">Total Kategori</div>
            </div>

            <div
                style="background: linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%); padding: 25px; border-radius: 15px; text-align: center;">
                <div style="font-size: 36px; color: #c2185b; font-weight: bold; margin-bottom: 10px;">
                    {{ number_format(\App\Models\Buku::sum('views')) }}
                </div>
                <div style="color: #546e7a; font-weight: 500;">Total Views</div>
            </div>
        </div>
    </div>

    <style>
        .choice-card {
            position: relative;
            overflow: hidden;
        }

        .choice-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(66, 165, 245, 0.1), transparent);
            transition: left 0.5s;
        }

        .choice-card:hover::before {
            left: 100%;
        }

        .choice-card.selected {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 10px 40px rgba(66, 165, 245, 0.1);
            }

            50% {
                box-shadow: 0 20px 60px rgba(66, 165, 245, 0.3);
            }

            100% {
                box-shadow: 0 10px 40px rgba(66, 165, 245, 0.1);
            }
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn:disabled:hover {
            transform: none !important;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.3) !important;
        }
    </style>

    <script>
        function selectChoice(jenis) {
            // Remove selected class from all cards
            document.querySelectorAll('.choice-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');

            // Check the radio button
            document.getElementById(jenis).checked = true;

            // Enable submit button
            document.getElementById('submitBtn').disabled = false;

            // Add smooth animation
            event.currentTarget.style.transform = 'scale(1.02)';
            setTimeout(() => {
                event.currentTarget.style.transform = 'scale(1)';
            }, 200);
        }

        // Add click event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation on page load
            const cards = document.querySelectorAll('.choice-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const selectedJenis = document.querySelector('input[name="jenis"]:checked');
                if (!selectedJenis) {
                    e.preventDefault();
                    showAlert('warning', 'Silakan pilih jenis buku terlebih dahulu!');
                    return false;
                }

                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                submitBtn.disabled = true;

                // Restore button after a short delay if submission fails
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                }, 5000);
            });
        });

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === '1') {
                selectChoice('fiksi');
            } else if (e.key === '2') {
                selectChoice('non_fiksi');
            } else if (e.key === 'Enter') {
                const submitBtn = document.getElementById('submitBtn');
                if (!submitBtn.disabled) {
                    submitBtn.click();
                }
            }
        });

        // Show alert function (if not already defined)
        function showAlert(type, message) {
            const alertHtml = `
        <div class="alert alert-${type}">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle'}"></i>
            ${message}
        </div>
    `;

            const mainContent = document.querySelector('.main-content');
            mainContent.insertAdjacentHTML('afterbegin', alertHtml);

            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = mainContent.querySelector('.alert');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }
    </script>
@endsection
