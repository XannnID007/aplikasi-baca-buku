@extends('admin.layouts.app')

@section('title', 'Detail Buku')
@section('subtitle', $buku->judul)

@section('content')
    <!-- Book Header -->
    <div class="card" style="margin-bottom: 30px;">
        <div style="display: grid; grid-template-columns: 200px 1fr auto; gap: 30px; padding: 30px; align-items: start;">
            <!-- Book Cover -->
            <div>
                <div
                    style="width: 100%; height: 280px; border-radius: 10px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    @if ($buku->cover_gambar)
                        <img src="{{ Storage::url($buku->cover_gambar) }}" alt="{{ $buku->judul }}"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div
                            style="display: flex; align-items: center; justify-content: center; height: 100%; background: #333;">
                            <i class="fas fa-book" style="font-size: 48px; color: #666;"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Book Info -->
            <div>
                <h1 style="color: #fff; font-size: 32px; margin-bottom: 10px;">{{ $buku->judul }}</h1>
                <p style="color: #b0b0b0; font-size: 18px; margin-bottom: 15px;">
                    <i class="fas fa-user"></i> {{ $buku->penulis }}
                </p>

                <!-- Book Meta -->
                <div
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 20px;">
                    <div style="background: #2d2d2d; padding: 15px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 20px; color: #ffc107; margin-bottom: 5px;">
                            <i class="fas fa-star"></i>
                        </div>
                        <div style="color: #fff; font-weight: bold;">{{ number_format($buku->rating_rata_rata, 1) }}</div>
                        <div style="color: #888; font-size: 12px;">{{ $buku->total_ratings }} rating</div>
                    </div>

                    <div style="background: #2d2d2d; padding: 15px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 20px; color: #007bff; margin-bottom: 5px;">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div style="color: #fff; font-weight: bold;">{{ number_format($buku->views) }}</div>
                        <div style="color: #888; font-size: 12px;">views</div>
                    </div>

                    <div style="background: #2d2d2d; padding: 15px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 20px; color: #28a745; margin-bottom: 5px;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div style="color: #fff; font-weight: bold;">{{ number_format($buku->halaman) }}</div>
                        <div style="color: #888; font-size: 12px;">halaman</div>
                    </div>

                    <div style="background: #2d2d2d; padding: 15px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 20px; color: #ffc107; margin-bottom: 5px;">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div style="color: #fff; font-weight: bold;">{{ $buku->tahun_terbit }}</div>
                        <div style="color: #888; font-size: 12px;">tahun</div>
                    </div>
                </div>

                <!-- Categories -->
                <div style="margin-bottom: 20px;">
                    <h6 style="color: #888; margin-bottom: 10px; text-transform: uppercase; font-size: 12px;">Kategori</h6>
                    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                        @foreach ($buku->kategoris as $kategori)
                            <span
                                style="background: #007bff; color: white; padding: 4px 12px; border-radius: 15px; font-size: 12px;">
                                {{ $kategori->nama }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Book Type -->
                <div style="margin-bottom: 20px;">
                    <span
                        style="background: {{ $buku->jenis == 'fiksi' ? '#e91e63' : '#2196f3' }}; color: white; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: bold;">
                        <i class="fas fa-{{ $buku->jenis == 'fiksi' ? 'dragon' : 'graduation-cap' }}"></i>
                        {{ $buku->jenis == 'fiksi' ? 'Fiksi' : 'Non-Fiksi' }}
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; flex-direction: column; gap: 10px; min-width: 150px;">
                <a href="{{ route('admin.buku.edit', $buku) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Buku
                </a>
                <a href="{{ route('buku.show', $buku) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Lihat Public
                </a>
                <a href="{{ Storage::url($buku->file_path) }}" class="btn btn-success" download>
                    <i class="fas fa-download"></i> Download
                </a>
                <button onclick="deleteBuku({{ $buku->id }})" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </button>
                <a href="{{ route('admin.buku.index') }}" class="btn btn-outline"
                    style="background: #2d2d2d; border-color: #555; color: #e0e0e0;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-align-left"></i> Deskripsi Buku
            </h3>
        </div>
        <div style="padding: 30px;">
            <p style="color: #e0e0e0; line-height: 1.8; font-size: 16px;">
                {{ $buku->deskripsi }}
            </p>
        </div>
    </div>

    <!-- Statistics -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Reading Stats -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i> Statistik Bacaan
                </h3>
            </div>
            <div style="padding: 30px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="text-align: center; padding: 20px; background: #1a1a1a; border-radius: 8px;">
                        <div style="font-size: 32px; color: #007bff; margin-bottom: 10px;">
                            {{ $buku->bookmarks->count() }}
                        </div>
                        <div style="color: #b0b0b0; font-size: 14px;">Bookmarks</div>
                    </div>

                    <div style="text-align: center; padding: 20px; background: #1a1a1a; border-radius: 8px;">
                        <div style="font-size: 32px; color: #28a745; margin-bottom: 10px;">
                            {{ $buku->riwayatBacaans->count() }}
                        </div>
                        <div style="color: #b0b0b0; font-size: 14px;">Pembaca</div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <h6 style="color: #888; margin-bottom: 10px;">Progress Bacaan Rata-rata</h6>
                    @php
                        $avgProgress = $buku->riwayatBacaans->avg('halaman_terakhir');
                        $progressPercent = $buku->halaman > 0 ? ($avgProgress / $buku->halaman) * 100 : 0;
                    @endphp
                    <div style="background: #333; height: 8px; border-radius: 4px; overflow: hidden;">
                        <div
                            style="width: {{ $progressPercent }}%; height: 100%; background: linear-gradient(135deg, #007bff 0%, #28a745 100%);">
                        </div>
                    </div>
                    <div style="margin-top: 5px; color: #888; font-size: 12px;">
                        {{ number_format($progressPercent, 1) }}% ({{ number_format($avgProgress ?? 0) }} dari
                        {{ $buku->halaman }} halaman)
                    </div>
                </div>
            </div>
        </div>

        <!-- File Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file"></i> Informasi File
                </h3>
            </div>
            <div style="padding: 30px;">
                <div style="margin-bottom: 20px;">
                    <h6 style="color: #888; margin-bottom: 10px;">File Buku</h6>
                    <div
                        style="background: #1a1a1a; padding: 15px; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="color: #fff; font-weight: 500;">{{ basename($buku->file_path) }}</div>
                            <div style="color: #888; font-size: 12px;">
                                @if (Storage::exists('public/' . $buku->file_path))
                                    <i class="fas fa-check-circle" style="color: #28a745;"></i> File tersedia
                                    @php
                                        $fileSize = Storage::size('public/' . $buku->file_path);
                                        $fileSizeMB = round($fileSize / 1024 / 1024, 2);
                                    @endphp
                                    ({{ $fileSizeMB }} MB)
                                @else
                                    <i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> File tidak ditemukan
                                @endif
                            </div>
                        </div>
                        @if (Storage::exists('public/' . $buku->file_path))
                            <a href="{{ Storage::url($buku->file_path) }}" class="btn btn-sm btn-primary" download>
                                <i class="fas fa-download"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div>
                    <h6 style="color: #888; margin-bottom: 10px;">Cover Gambar</h6>
                    <div style="background: #1a1a1a; padding: 15px; border-radius: 8px;">
                        @if ($buku->cover_gambar)
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div>
                                    <div style="color: #fff; font-weight: 500;">{{ basename($buku->cover_gambar) }}</div>
                                    <div style="color: #888; font-size: 12px;">
                                        @if (Storage::exists('public/' . $buku->cover_gambar))
                                            <i class="fas fa-check-circle" style="color: #28a745;"></i> Gambar tersedia
                                        @else
                                            <i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> Gambar
                                            tidak ditemukan
                                        @endif
                                    </div>
                                </div>
                                @if (Storage::exists('public/' . $buku->cover_gambar))
                                    <a href="{{ Storage::url($buku->cover_gambar) }}" target="_blank"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                            </div>
                        @else
                            <div style="color: #888; text-align: center; padding: 20px;">
                                <i class="fas fa-image" style="font-size: 32px; margin-bottom: 10px;"></i>
                                <div>Tidak ada cover gambar</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ratings & Reviews -->
    @if ($buku->ratings->count() > 0)
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star"></i> Rating & Review ({{ $buku->ratings->count() }})
                </h3>
            </div>
            <div style="padding: 30px;">
                <!-- Rating Summary -->
                <div style="display: grid; grid-template-columns: 200px 1fr; gap: 30px; margin-bottom: 30px;">
                    <div style="text-align: center;">
                        <div style="font-size: 48px; color: #ffc107; margin-bottom: 10px;">
                            {{ number_format($buku->rating_rata_rata, 1) }}
                        </div>
                        <div style="margin-bottom: 10px;">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star"
                                    style="color: {{ $i <= floor($buku->rating_rata_rata) ? '#ffc107' : '#333' }}; font-size: 20px;"></i>
                            @endfor
                        </div>
                        <div style="color: #888; font-size: 14px;">{{ $buku->total_ratings }} rating</div>
                    </div>

                    <div>
                        @for ($i = 5; $i >= 1; $i--)
                            @php $count = $buku->ratings->where('rating', $i)->count(); @endphp
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <span style="color: #888; width: 60px; font-size: 14px;">{{ $i }}
                                    bintang</span>
                                <div
                                    style="flex: 1; background: #333; height: 8px; border-radius: 4px; margin: 0 15px; overflow: hidden;">
                                    <div
                                        style="width: {{ $buku->total_ratings > 0 ? ($count / $buku->total_ratings) * 100 : 0 }}%; height: 100%; background: #ffc107;">
                                    </div>
                                </div>
                                <span style="color: #888; width: 40px; font-size: 14px;">{{ $count }}</span>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Recent Reviews -->
                <h6 style="color: #888; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px;">Review
                    Terbaru</h6>
                <div style="max-height: 400px; overflow-y: auto;">
                    @foreach ($buku->ratings->sortByDesc('created_at')->take(10) as $rating)
                        <div style="border-bottom: 1px solid #2d2d2d; padding: 15px 0;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                                <div>
                                    <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                        <strong
                                            style="color: #fff; margin-right: 10px;">{{ $rating->user->name }}</strong>
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star"
                                                    style="color: {{ $i <= $rating->rating ? '#ffc107' : '#333' }}; font-size: 14px;"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <div style="color: #888; font-size: 12px;">{{ $rating->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @if ($rating->review)
                                <p style="color: #e0e0e0; line-height: 1.6; margin: 0; font-style: italic;">
                                    "{{ $rating->review }}"
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history"></i> Aktivitas Terbaru
            </h3>
        </div>
        <div style="padding: 30px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Recent Readers -->
                <div>
                    <h6 style="color: #888; margin-bottom: 15px;">Pembaca Terbaru</h6>
                    @if ($buku->riwayatBacaans->count() > 0)
                        <div style="max-height: 300px; overflow-y: auto;">
                            @foreach ($buku->riwayatBacaans->sortByDesc('terakhir_dibaca')->take(10) as $riwayat)
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #2d2d2d;">
                                    <div>
                                        <div style="color: #fff; font-weight: 500;">{{ $riwayat->user->name }}</div>
                                        <div style="color: #888; font-size: 12px;">
                                            Halaman {{ $riwayat->halaman_terakhir }} dari {{ $buku->halaman }}
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="color: #888; font-size: 12px;">
                                            {{ $riwayat->terakhir_dibaca->diffForHumans() }}</div>
                                        @php $progress = ($riwayat->halaman_terakhir / $buku->halaman) * 100; @endphp
                                        <div style="color: #007bff; font-size: 12px; font-weight: 500;">
                                            {{ number_format($progress, 1) }}%</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; color: #666; padding: 20px;">
                            <i class="fas fa-book-reader" style="font-size: 32px; margin-bottom: 10px;"></i>
                            <div>Belum ada yang membaca</div>
                        </div>
                    @endif
                </div>

                <!-- Recent Bookmarks -->
                <div>
                    <h6 style="color: #888; margin-bottom: 15px;">Bookmark Terbaru</h6>
                    @if ($buku->bookmarks->count() > 0)
                        <div style="max-height: 300px; overflow-y: auto;">
                            @foreach ($buku->bookmarks->sortByDesc('created_at')->take(10) as $bookmark)
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #2d2d2d;">
                                    <div>
                                        <div style="color: #fff; font-weight: 500;">{{ $bookmark->user->name }}</div>
                                        <div style="color: #888; font-size: 12px;">{{ $bookmark->user->email }}</div>
                                    </div>
                                    <div style="color: #888; font-size: 12px;">
                                        {{ $bookmark->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; color: #666; padding: 20px;">
                            <i class="fas fa-bookmark" style="font-size: 32px; margin-bottom: 10px;"></i>
                            <div>Belum ada bookmark</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteBuku(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus buku ini?\n\nTindakan ini akan:\n- Menghapus semua data buku\n- Menghapus semua bookmark pengguna\n- Menghapus semua riwayat bacaan\n- Menghapus semua rating dan review\n\nTindakan ini TIDAK DAPAT DIBATALKAN!'
                    )) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/buku/${id}`;
                form.style.display = 'none';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = '{{ csrf_token() }}';

                form.appendChild(methodField);
                form.appendChild(tokenField);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Add scroll animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endsection
