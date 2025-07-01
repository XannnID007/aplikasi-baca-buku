@extends('admin.layouts.app')

@section('title', 'Kelola Buku')
@section('subtitle', 'Manajemen data buku dalam perpustakaan digital')

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-books"></i> Daftar Buku
                    </h3>
                    <p style="color: #b0b0b0; font-size: 14px; margin-top: 5px;">
                        Total: {{ $bukus->total() }} buku
                    </p>
                </div>
                <a href="{{ route('admin.buku.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Buku
                </a>
            </div>
        </div>

        <!-- Search and Filter -->
        <div style="padding: 20px; border-bottom: 1px solid #333;">
            <form method="GET" action="{{ route('admin.buku.index') }}">
                <div style="display: grid; grid-template-columns: 1fr 200px 200px auto; gap: 15px; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Cari Buku</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Judul, penulis, atau deskripsi..." value="{{ request('search') }}">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-control">
                            <option value="">Semua Jenis</option>
                            <option value="fiksi" {{ request('jenis') == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
                            <option value="non_fiksi" {{ request('jenis') == 'non_fiksi' ? 'selected' : '' }}>Non-Fiksi
                            </option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Urutkan</label>
                        <select name="sort" class="form-control">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Judul A-Z</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Populer</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-outline"
                            style="background: #2d2d2d; border-color: #555; color: #e0e0e0;">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Informasi Buku</th>
                        <th>Detail</th>
                        <th>Kategori</th>
                        <th>Statistik</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $buku)
                        <tr>
                            <td>
                                <div
                                    style="width: 60px; height: 80px; border-radius: 8px; overflow: hidden; background: #333;">
                                    @if ($buku->cover_gambar)
                                        <img src="{{ Storage::url($buku->cover_gambar) }}" alt="{{ $buku->judul }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div
                                            style="display: flex; align-items: center; justify-content: center; height: 100%; color: #666;">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong
                                        style="color: #fff; font-size: 16px;">{{ Str::limit($buku->judul, 40) }}</strong>
                                    <div style="color: #b0b0b0; font-size: 14px; margin-top: 2px;">
                                        <i class="fas fa-user"></i> {{ $buku->penulis }}
                                    </div>
                                    <div style="color: #888; font-size: 12px; margin-top: 4px;">
                                        {{ Str::limit($buku->deskripsi, 80) }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12px;">
                                    <div style="color: #b0b0b0; margin-bottom: 3px;">
                                        <i class="fas fa-calendar"></i> {{ $buku->tahun_terbit }}
                                    </div>
                                    <div style="color: #b0b0b0; margin-bottom: 3px;">
                                        <i class="fas fa-file-alt"></i> {{ number_format($buku->halaman) }} hal
                                    </div>
                                    <div style="color: #b0b0b0;">
                                        <i class="fas fa-tag"></i> {{ ucfirst(str_replace('_', ' ', $buku->jenis)) }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 150px;">
                                    @foreach ($buku->kategoris as $kategori)
                                        <span
                                            style="background: #007bff; color: white; padding: 2px 8px; border-radius: 10px; font-size: 10px;">
                                            {{ $kategori->nama }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12px;">
                                    <div style="color: #b0b0b0; margin-bottom: 3px;">
                                        <i class="fas fa-eye"></i> {{ number_format($buku->views) }}
                                    </div>
                                    <div style="color: #b0b0b0; margin-bottom: 3px;">
                                        <i class="fas fa-star" style="color: #ffc107;"></i>
                                        {{ number_format($buku->rating_rata_rata, 1) }}
                                    </div>
                                    <div style="color: #b0b0b0;">
                                        <i class="fas fa-comment"></i> {{ $buku->total_ratings }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($buku->file_path && Storage::exists('public/' . $buku->file_path))
                                    <span
                                        style="background: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px;">
                                        <i class="fas fa-check"></i> Aktif
                                    </span>
                                @else
                                    <span
                                        style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px;">
                                        <i class="fas fa-exclamation"></i> File Hilang
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="{{ route('admin.buku.show', $buku) }}" class="btn btn-sm"
                                        style="background: #17a2b8; color: white;" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.buku.edit', $buku) }}" class="btn btn-warning btn-sm"
                                        title="Edit Buku">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteBuku({{ $buku->id }})" class="btn btn-danger btn-sm"
                                        title="Hapus Buku">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #666;">
                                <i class="fas fa-book" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                @if (request()->hasAny(['search', 'jenis', 'sort']))
                                    <h4>Tidak ada buku yang ditemukan</h4>
                                    <p>Coba ubah kriteria pencarian atau filter</p>
                                    <a href="{{ route('admin.buku.index') }}" class="btn btn-primary"
                                        style="margin-top: 15px;">
                                        <i class="fas fa-undo"></i> Reset Filter
                                    </a>
                                @else
                                    <h4>Belum ada buku</h4>
                                    <p>Mulai dengan menambahkan buku pertama</p>
                                    <a href="{{ route('admin.buku.create') }}" class="btn btn-primary"
                                        style="margin-top: 15px;">
                                        <i class="fas fa-plus"></i> Tambah Buku
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bukus->hasPages())
            <div style="padding: 20px; border-top: 1px solid #333;">
                {{ $bukus->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 30px;">
        <div class="card" style="text-align: center;">
            <h3 style="color: #007bff; font-size: 32px; margin-bottom: 10px;">
                {{ \App\Models\Buku::where('jenis', 'fiksi')->count() }}
            </h3>
            <p style="color: #b0b0b0;">Buku Fiksi</p>
        </div>

        <div class="card" style="text-align: center;">
            <h3 style="color: #28a745; font-size: 32px; margin-bottom: 10px;">
                {{ \App\Models\Buku::where('jenis', 'non_fiksi')->count() }}
            </h3>
            <p style="color: #b0b0b0;">Buku Non-Fiksi</p>
        </div>

        <div class="card" style="text-align: center;">
            <h3 style="color: #ffc107; font-size: 32px; margin-bottom: 10px;">
                {{ number_format(\App\Models\Buku::sum('views')) }}
            </h3>
            <p style="color: #b0b0b0;">Total Views</p>
        </div>

        <div class="card" style="text-align: center;">
            <h3 style="color: #dc3545; font-size: 32px; margin-bottom: 10px;">
                {{ number_format(\App\Models\Buku::avg('rating_rata_rata'), 1) }}
            </h3>
            <p style="color: #b0b0b0;">Rating Rata-rata</p>
        </div>
    </div>

    <script>
        function deleteBuku(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus buku ini?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait (bookmark, rating, history bacaan).'
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

        // Auto submit form on select change
        document.querySelectorAll('select[name="jenis"], select[name="sort"]').forEach(select => {
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });

        // Enhanced search with debouncing
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        this.closest('form').submit();
                    }
                }, 500);
            });
        }
    </script>
@endsection
