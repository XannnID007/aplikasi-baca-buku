@extends('layouts.app')

@section('title', 'Pilih Kategori')

@section('content')
    <div class="hero">
        <h1><i class="fas fa-tags"></i> Pilih Kategori Favorit Anda</h1>
        <p>Pilih beberapa kategori yang paling Anda minati untuk mendapatkan rekomendasi buku yang tepat</p>
        <div
            style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 15px 30px; border-radius: 25px; display: inline-block; margin-top: 20px;">
            <strong style="color: #1976d2;">
                <i class="fas fa-{{ request('jenis') == 'fiksi' ? 'dragon' : 'graduation-cap' }}"></i>
                Jenis: {{ ucfirst(str_replace('_', ' ', request('jenis'))) }}
            </strong>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-list-check"></i> Kategori
                {{ request('jenis') == 'fiksi' ? 'Fiksi' : 'Non-Fiksi' }}
            </h2>
            <p class="card-subtitle">Pilih minimal 1 kategori yang sesuai dengan minat Anda (bisa lebih dari satu)</p>
        </div>

        <form action="{{ route('pilih.kategori.proses') }}" method="POST" id="categoryForm">
            @csrf
            <input type="hidden" name="jenis" value="{{ request('jenis') }}">

            <div class="category-grid">
                @foreach ($kategoris as $kategori)
                    <div class="category-checkbox">
                        <input type="checkbox" name="kategori_ids[]" value="{{ $kategori->id }}"
                            id="kategori_{{ $kategori->id }}">
                        <label for="kategori_{{ $kategori->id }}" class="category-label">
                            <div class="category-icon">
                                @if (request('jenis') == 'fiksi')
                                    @switch($kategori->nama)
                                        @case('Romantis')
                                            <i class="fas fa-heart"></i>
                                        @break

                                        @case('Horor')
                                            <i class="fas fa-ghost"></i>
                                        @break

                                        @case('Petualangan')
                                            <i class="fas fa-compass"></i>
                                        @break

                                        @case('Fantasi')
                                            <i class="fas fa-magic"></i>
                                        @break

                                        @case('Sci-Fi')
                                            <i class="fas fa-rocket"></i>
                                        @break

                                        @case('Thriller')
                                            <i class="fas fa-skull"></i>
                                        @break

                                        @case('Komedi')
                                            <i class="fas fa-laugh"></i>
                                        @break

                                        @case('Drama')
                                            <i class="fas fa-theater-masks"></i>
                                        @break

                                        @case('Misteri')
                                            <i class="fas fa-search"></i>
                                        @break

                                        @default
                                            <i class="fas fa-book"></i>
                                    @endswitch
                                @else
                                    @switch($kategori->nama)
                                        @case('Pendidikan')
                                            <i class="fas fa-graduation-cap"></i>
                                        @break

                                        @case('Teknologi')
                                            <i class="fas fa-laptop-code"></i>
                                        @break

                                        @case('Biografi')
                                            <i class="fas fa-user"></i>
                                        @break

                                        @case('Sains')
                                            <i class="fas fa-atom"></i>
                                        @break

                                        @case('Sejarah')
                                            <i class="fas fa-landmark"></i>
                                        @break

                                        @case('Kesehatan')
                                            <i class="fas fa-heartbeat"></i>
                                        @break

                                        @case('Bisnis')
                                            <i class="fas fa-chart-line"></i>
                                        @break

                                        @case('Psikologi')
                                            <i class="fas fa-brain"></i>
                                        @break

                                        @case('Agama')
                                            <i class="fas fa-praying-hands"></i>
                                        @break

                                        @case('Seni')
                                            <i class="fas fa-palette"></i>
                                        @break

                                        @default
                                            <i class="fas fa-book"></i>
                                    @endswitch
                                @endif
                            </div>
                            <h4>{{ $kategori->nama }}</h4>
                            @if ($kategori->deskripsi)
                                <p style="font-size: 12px; color: #78909c; margin-top: 8px; line-height: 1.4;">
                                    {{ Str::limit($kategori->deskripsi, 60) }}
                                </p>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>

            <div style="text-align: center; margin-top: 40px;">
                <div style="margin-bottom: 20px;">
                    <span style="color: #546e7a; font-size: 14px;">
                        <i class="fas fa-info-circle"></i>
                        Dipilih: <span id="selectedCount">0</span> kategori
                    </span>
                </div>

                <button type="button" onclick="history.back()" class="btn btn-outline" style="margin-right: 15px;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>

                <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 18px;" id="submitBtn"
                    disabled>
                    <i class="fas fa-search"></i> Dapatkan Rekomendasi
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Select -->
    <div class="card" style="margin-top: 40px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bolt"></i> Pilihan Cepat
            </h3>
            <p class="card-subtitle">Pilih set kategori yang sudah disiapkan berdasarkan minat umum</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            @if (request('jenis') == 'fiksi')
                <div class="quick-select-card" onclick="quickSelect(['Romantis', 'Drama'])">
                    <div style="font-size: 32px; color: #e91e63; margin-bottom: 10px;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Pecinta Romance</h4>
                    <p>Romantis & Drama</p>
                </div>

                <div class="quick-select-card" onclick="quickSelect(['Horor', 'Thriller', 'Misteri'])">
                    <div style="font-size: 32px; color: #9c27b0; margin-bottom: 10px;">
                        <i class="fas fa-ghost"></i>
                    </div>
                    <h4>Penggemar Thriller</h4>
                    <p>Horor, Thriller & Misteri</p>
                </div>

                <div class="quick-select-card" onclick="quickSelect(['Fantasi', 'Sci-Fi', 'Petualangan'])">
                    <div style="font-size: 32px; color: #3f51b5; margin-bottom: 10px;">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4>Petualang Fantasi</h4>
                    <p>Fantasi, Sci-Fi & Petualangan</p>
                </div>
            @else
                <div class="quick-select-card" onclick="quickSelect(['Teknologi', 'Sains'])">
                    <div style="font-size: 32px; color: #2196f3; margin-bottom: 10px;">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h4>Tech Enthusiast</h4>
                    <p>Teknologi & Sains</p>
                </div>

                <div class="quick-select-card" onclick="quickSelect(['Bisnis', 'Pendidikan'])">
                    <div style="font-size: 32px; color: #4caf50; margin-bottom: 10px;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Pengembang Diri</h4>
                    <p>Bisnis & Pendidikan</p>
                </div>

                <div class="quick-select-card" onclick="quickSelect(['Biografi', 'Sejarah'])">
                    <div style="font-size: 32px; color: #ff9800; margin-bottom: 10px;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4>Pencinta Sejarah</h4>
                    <p>Biografi & Sejarah</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .category-checkbox input:checked+.category-label {
            transform: scale(1.05);
        }

        .quick-select-card {
            background: white;
            border: 2px solid #e3f2fd;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .quick-select-card:hover {
            border-color: #42a5f5;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(66, 165, 245, 0.2);
        }

        .quick-select-card h4 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .quick-select-card p {
            color: #546e7a;
            font-size: 12px;
            margin: 0;
        }

        .category-label {
            position: relative;
            overflow: hidden;
        }

        .category-label::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(66, 165, 245, 0.1), transparent);
            transition: left 0.5s;
        }

        .category-label:hover::before {
            left: 100%;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(45deg);
            }

            50% {
                transform: scale(1.2) rotate(45deg);
            }

            100% {
                transform: scale(1) rotate(45deg);
            }
        }

        .category-checkbox input:checked+.category-label::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #42a5f5;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            animation: checkmark 0.3s ease;
        }
    </style>

    <script>
        let selectedCategories = [];

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('input[name="kategori_ids[]"]:checked');
            const count = checkboxes.length;
            document.getElementById('selectedCount').textContent = count;
            document.getElementById('submitBtn').disabled = count === 0;

            // Update submit button text based on selection
            const submitBtn = document.getElementById('submitBtn');
            if (count === 0) {
                submitBtn.innerHTML = '<i class="fas fa-search"></i> Pilih Minimal 1 Kategori';
            } else {
                submitBtn.innerHTML = `<i class="fas fa-search"></i> Dapatkan Rekomendasi (${count} kategori)`;
            }
        }

        function quickSelect(categoryNames) {
            // First, uncheck all checkboxes
            document.querySelectorAll('input[name="kategori_ids[]"]').forEach(cb => {
                cb.checked = false;
                cb.closest('.category-checkbox').querySelector('.category-label').classList.remove('selected');
            });

            // Then check the specified categories
            categoryNames.forEach(name => {
                const kategoriLabels = document.querySelectorAll('.category-label');
                kategoriLabels.forEach(label => {
                    const h4 = label.querySelector('h4');
                    if (h4 && h4.textContent.trim() === name) {
                        const checkbox = label.closest('.category-checkbox').querySelector('input');
                        if (checkbox) {
                            checkbox.checked = true;
                            label.classList.add('selected');
                        }
                    }
                });
            });

            updateSelectedCount();

            // Visual feedback
            const quickCards = document.querySelectorAll('.quick-select-card');
            quickCards.forEach(card => {
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.transform = 'scale(1)';
                }, 100);
            });
        }

        // Add event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Add change listeners to all checkboxes
            document.querySelectorAll('input[name="kategori_ids[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });

            // Add animation on page load
            const categories = document.querySelectorAll('.category-checkbox');
            categories.forEach((category, index) => {
                category.style.opacity = '0';
                category.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    category.style.transition = 'all 0.4s ease';
                    category.style.opacity = '1';
                    category.style.transform = 'translateY(0)';
                }, index * 50);
            });

            // Form validation
            document.getElementById('categoryForm').addEventListener('submit', function(e) {
                const selectedCategories = document.querySelectorAll(
                'input[name="kategori_ids[]"]:checked');
                if (selectedCategories.length === 0) {
                    e.preventDefault();
                    showAlert('warning', 'Silakan pilih minimal satu kategori!');
                    return false;
                }

                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menganalisis Preferensi...';
                submitBtn.disabled = true;

                // Restore button after a short delay if submission fails
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.innerHTML = originalText;
                        updateSelectedCount();
                    }
                }, 10000);
            });

            // Initial count update
            updateSelectedCount();
        });

        // Select all categories shortcut
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'a') {
                e.preventDefault();
                document.querySelectorAll('input[name="kategori_ids[]"]').forEach(cb => {
                    cb.checked = true;
                });
                updateSelectedCount();
            }

            // Clear all selections
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                document.querySelectorAll('input[name="kategori_ids[]"]').forEach(cb => {
                    cb.checked = false;
                });
                updateSelectedCount();
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
