@extends('admin.layouts.app')

@section('title', 'Kelola Kategori')
@section('subtitle', 'Manajemen kategori buku fiksi dan non-fiksi')

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-tags"></i> Daftar Kategori
                    </h3>
                    <p style="color: #b0b0b0; font-size: 14px; margin-top: 5px;">
                        Total: {{ $kategoris->total() }} kategori
                    </p>
                </div>
                <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div style="padding: 20px; border-bottom: 1px solid #333;">
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <a href="{{ route('admin.kategori.index') }}"
                    class="btn {{ !request('jenis') ? 'btn-primary' : 'btn-outline' }}"
                    style="{{ !request('jenis') ? '' : 'background: #2d2d2d; border-color: #555; color: #e0e0e0;' }}">
                    <i class="fas fa-list"></i> Semua ({{ \App\Models\Kategori::count() }})
                </a>
                <a href="{{ route('admin.kategori.index', ['jenis' => 'fiksi']) }}"
                    class="btn {{ request('jenis') == 'fiksi' ? 'btn-primary' : 'btn-outline' }}"
                    style="{{ request('jenis') == 'fiksi' ? '' : 'background: #2d2d2d; border-color: #555; color: #e0e0e0;' }}">
                    <i class="fas fa-dragon"></i> Fiksi ({{ \App\Models\Kategori::where('jenis', 'fiksi')->count() }})
                </a>
                <a href="{{ route('admin.kategori.index', ['jenis' => 'non_fiksi']) }}"
                    class="btn {{ request('jenis') == 'non_fiksi' ? 'btn-primary' : 'btn-outline' }}"
                    style="{{ request('jenis') == 'non_fiksi' ? '' : 'background: #2d2d2d; border-color: #555; color: #e0e0e0;' }}">
                    <i class="fas fa-graduation-cap"></i> Non-Fiksi
                    ({{ \App\Models\Kategori::where('jenis', 'non_fiksi')->count() }})
                </a>
            </div>

            <!-- Search -->
            <form method="GET" action="{{ route('admin.kategori.index') }}">
                @if (request('jenis'))
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                @endif
                <div style="display: flex; gap: 15px; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1;">
                        <label class="form-label">Cari Kategori</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Nama atau deskripsi kategori..." value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if (request()->hasAny(['search', 'jenis']))
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline"
                            style="background: #2d2d2d; border-color: #555; color: #e0e0e0;">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Categories Grid -->
        <div style="padding: 30px;">
            @if ($kategoris->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    @foreach ($kategoris as $kategori)
                        <div class="card" style="margin-bottom: 0; transition: all 0.3s; cursor: pointer;"
                            onclick="location.href='{{ route('admin.kategori.show', $kategori) }}'">

                            <!-- Category Header -->
                            <div style="padding: 20px 20px 15px; border-bottom: 1px solid #333;">
                                <div style="display: flex; justify-content: between; align-items: start;">
                                    <div style="flex: 1;">
                                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                            <div
                                                style="padding: 8px; background: {{ $kategori->jenis == 'fiksi' ? '#e91e63' : '#2196f3' }}; border-radius: 8px; margin-right: 12px;">
                                                <i class="fas fa-{{ $kategori->jenis == 'fiksi' ? 'dragon' : 'graduation-cap' }}"
                                                    style="color: white; font-size: 16px;"></i>
                                            </div>
                                            <div>
                                                <h4 style="color: #fff; margin: 0; font-size: 18px;">{{ $kategori->nama }}
                                                </h4>
                                                <span
                                                    style="color: #{{ $kategori->jenis == 'fiksi' ? 'e91e63' : '2196f3' }}; font-size: 12px; text-transform: uppercase; font-weight: 600;">
                                                    {{ $kategori->jenis == 'fiksi' ? 'Fiksi' : 'Non-Fiksi' }}
                                                </span>
                                            </div>
                                        </div>

                                        @if ($kategori->deskripsi)
                                            <p style="color: #b0b0b0; font-size: 14px; margin: 0; line-height: 1.4;">
                                                {{ Str::limit($kategori->deskripsi, 80) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Category Stats -->
                            <div style="padding: 15px 20px;">
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <div style="display: flex; align-items: center; color: #b0b0b0; font-size: 14px;">
                                        <i class="fas fa-book" style="margin-right: 8px; color: #007bff;"></i>
                                        <span>{{ $kategori->bukus_count }} buku</span>
                                    </div>
                                    <div style="color: #888; font-size: 12px;">
                                        <i class="fas fa-clock"></i>
                                        {{ $kategori->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div style="display: flex; gap: 8px;" onclick="event.stopPropagation();">
                                    <a href="{{ route('admin.kategori.show', $kategori) }}" class="btn btn-sm"
                                        style="background: #17a2b8; color: white; flex: 1;" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-warning btn-sm"
                                        style="flex: 1;" title="Edit Kategori">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button onclick="deleteKategori({{ $kategori->id }})" class="btn btn-danger btn-sm"
                                        title="Hapus Kategori" style="min-width: 44px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Popular Books Preview -->
                            @if ($kategori->bukus->count() > 0)
                                <div style="padding: 0 20px 20px;">
                                    <div style="border-top: 1px solid #333; padding-top: 15px;">
                                        <h6
                                            style="color: #888; font-size: 12px; margin-bottom: 10px; text-transform: uppercase;">
                                            Buku Terpopuler
                                        </h6>
                                        <div style="display: flex; gap: 8px; overflow-x: auto;">
                                            @foreach ($kategori->bukus->take(3) as $buku)
                                                <div style="min-width: 60px; text-align: center;">
                                                    <div
                                                        style="width: 40px; height: 50px; background: #333; border-radius: 4px; margin: 0 auto 5px; display: flex; align-items: center; justify-content: center;">
                                                        @if ($buku->cover_gambar)
                                                            <img src="{{ Storage::url($buku->cover_gambar) }}"
                                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                                        @else
                                                            <i class="fas fa-book"
                                                                style="color: #666; font-size: 12px;"></i>
                                                        @endif
                                                    </div>
                                                    <div style="color: #b0b0b0; font-size: 10px;">
                                                        {{ Str::limit($buku->judul, 15) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div style="text-align: center; padding: 60px 20px; color: #666;">
                    <i class="fas fa-tags" style="font-size: 64px; margin-bottom: 20px; display: block;"></i>
                    @if (request()->hasAny(['search', 'jenis']))
                        <h4>Tidak ada kategori yang ditemukan</h4>
                        <p>Coba ubah kriteria pencarian atau filter</p>
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-primary" style="margin-top: 20px;">
                            <i class="fas fa-undo"></i> Reset Filter
                        </a>
                    @else
                        <h4>Belum ada kategori</h4>
                        <p>Mulai dengan menambahkan kategori pertama untuk mengorganisir buku</p>
                        <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary"
                            style="margin-top: 20px;">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($kategoris->hasPages())
            <div style="padding: 20px 30px; border-top: 1px solid #333;">
                {{ $kategoris->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
        <div class="card" style="text-align: center;">
            <div style="padding: 25px;">
                <div style="font-size: 48px; color: #e91e63; margin-bottom: 15px;">
                    <i class="fas fa-dragon"></i>
                </div>
                <h3 style="color: #fff; font-size: 32px; margin-bottom: 10px;">
                    {{ \App\Models\Kategori::where('jenis', 'fiksi')->count() }}
                </h3>
                <p style="color: #b0b0b0; margin: 0;">Kategori Fiksi</p>
                <div style="margin-top: 15px; font-size: 12px; color: #888;">
                    {{ \App\Models\Buku::where('jenis', 'fiksi')->count() }} buku tersedia
                </div>
            </div>
        </div>

        <div class="card" style="text-align: center;">
            <div style="padding: 25px;">
                <div style="font-size: 48px; color: #2196f3; margin-bottom: 15px;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 style="color: #fff; font-size: 32px; margin-bottom: 10px;">
                    {{ \App\Models\Kategori::where('jenis', 'non_fiksi')->count() }}
                </h3>
                <p style="color: #b0b0b0; margin: 0;">Kategori Non-Fiksi</p>
                <div style="margin-top: 15px; font-size: 12px; color: #888;">
                    {{ \App\Models\Buku::where('jenis', 'non_fiksi')->count() }} buku tersedia
                </div>
            </div>
        </div>

        <div class="card" style="text-align: center;">
            <div style="padding: 25px;">
                <div style="font-size: 48px; color: #4caf50; margin-bottom: 15px;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 style="color: #fff; font-size: 32px; margin-bottom: 10px;">
                    {{ number_format(\App\Models\Kategori::withCount('bukus')->avg('bukus_count'), 1) }}
                </h3>
                <p style="color: #b0b0b0; margin: 0;">Rata-rata Buku per Kategori</p>
                <div style="margin-top: 15px; font-size: 12px; color: #888;">
                    Total {{ \App\Models\Buku::count() }} buku
                </div>
            </div>
        </div>

        <div class="card" style="text-align: center;">
            <div style="padding: 25px;">
                <div style="font-size: 48px; color: #ff9800; margin-bottom: 15px;">
                    <i class="fas fa-crown"></i>
                </div>
                <h3 style="color: #fff; font-size: 32px; margin-bottom: 10px;">
                    {{ \App\Models\Kategori::withCount('bukus')->orderBy('bukus_count', 'desc')->first()->bukus_count ?? 0 }}
                </h3>
                <p style="color: #b0b0b0; margin: 0;">Kategori Terpopuler</p>
                <div style="margin-top: 15px; font-size: 12px; color: #888;">
                    {{ \App\Models\Kategori::withCount('bukus')->orderBy('bukus_count', 'desc')->first()->nama ?? 'Belum ada data' }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteKategori(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus kategori ini?\n\nKategori yang dihapus akan dihilangkan dari semua buku yang menggunakannya.'
                    )) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/kategori/${id}`;
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

        // Add hover effects to category cards
        document.querySelectorAll('.card').forEach(card => {
            if (card.style.cursor === 'pointer') {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 15px 40px rgba(66, 165, 245, 0.2)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
                });
            }
        });

        // Enhanced search with debouncing
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 2 || this.value.length === 0) {
                        this.closest('form').submit();
                    }
                }, 500);
            });
        }
    </script>
@endsection
