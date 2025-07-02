@extends('admin.layouts.app')

@section('title', 'Detail Kategori')
@section('subtitle', $kategori->nama)

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">
                    <i class="fas fa-info-circle"></i> Detail Kategori
                </h3>
                <div>
                    <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-warning"
                        style="margin-right: 10px;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline"
                        style="background: #2d2d2d; border-color: #555; color: #e0e0e0;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div style="padding: 30px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <div>
                    <h5 style="color: #888; margin-bottom: 5px;">Nama Kategori</h5>
                    <p style="color: #fff; font-size: 18px; font-weight: 600;">{{ $kategori->nama }}</p>
                </div>

                <div>
                    <h5 style="color: #888; margin-bottom: 5px;">Jenis</h5>
                    <span
                        style="background: {{ $kategori->jenis == 'fiksi' ? '#e91e63' : '#2196f3' }}; color: white; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: bold;">
                        <i class="fas fa-{{ $kategori->jenis == 'fiksi' ? 'dragon' : 'graduation-cap' }}"></i>
                        {{ $kategori->jenis == 'fiksi' ? 'Fiksi' : 'Non-Fiksi' }}
                    </span>
                </div>
            </div>

            @if ($kategori->deskripsi)
                <div style="margin-bottom: 30px;">
                    <h5 style="color: #888; margin-bottom: 10px;">Deskripsi</h5>
                    <p style="color: #e0e0e0; line-height: 1.6;">{{ $kategori->deskripsi }}</p>
                </div>
            @endif

            <div style="margin-bottom: 30px;">
                <h5 style="color: #888; margin-bottom: 15px;">Statistik</h5>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div style="background: #2d2d2d; padding: 20px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 28px; color: #42a5f5; font-weight: bold; margin-bottom: 5px;">
                            {{ $kategori->bukus->count() }}
                        </div>
                        <div style="color: #b0b0b0; font-size: 14px;">Total Buku</div>
                    </div>

                    <div style="background: #2d2d2d; padding: 20px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 28px; color: #66bb6a; font-weight: bold; margin-bottom: 5px;">
                            {{ $kategori->bukus->sum('views') }}
                        </div>
                        <div style="color: #b0b0b0; font-size: 14px;">Total Views</div>
                    </div>

                    <div style="background: #2d2d2d; padding: 20px; border-radius: 10px; text-align: center;">
                        <div style="font-size: 28px; color: #ffc107; font-weight: bold; margin-bottom: 5px;">
                            {{ $kategori->bukus->avg('rating_rata_rata') ? number_format($kategori->bukus->avg('rating_rata_rata'), 1) : '0' }}
                        </div>
                        <div style="color: #b0b0b0; font-size: 14px;">Rating Rata-rata</div>
                    </div>
                </div>
            </div>

            @if ($kategori->bukus->count() > 0)
                <div>
                    <h5 style="color: #888; margin-bottom: 15px;">Daftar Buku ({{ $kategori->bukus->count() }})</h5>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                        @foreach ($kategori->bukus->take(6) as $buku)
                            <div style="background: #2d2d2d; border-radius: 10px; overflow: hidden;">
                                <div
                                    style="height: 120px; background: #333; display: flex; align-items: center; justify-content: center;">
                                    @if ($buku->cover_gambar)
                                        <img src="{{ Storage::url($buku->cover_gambar) }}" alt="{{ $buku->judul }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-book" style="font-size: 32px; color: #666;"></i>
                                    @endif
                                </div>
                                <div style="padding: 15px;">
                                    <h6 style="color: #fff; margin-bottom: 5px;">{{ Str::limit($buku->judul, 30) }}</h6>
                                    <p style="color: #b0b0b0; font-size: 12px; margin: 0;">{{ $buku->penulis }}</p>
                                    <div style="margin-top: 10px;">
                                        <a href="{{ route('admin.buku.show', $buku) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($kategori->bukus->count() > 6)
                        <div style="text-align: center; margin-top: 20px;">
                            <a href="{{ route('admin.buku.index', ['kategori' => $kategori->id]) }}"
                                class="btn btn-outline">
                                <i class="fas fa-list"></i> Lihat Semua Buku ({{ $kategori->bukus->count() }})
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div style="text-align: center; padding: 40px; color: #666;">
                    <i class="fas fa-book" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <h4>Belum ada buku dalam kategori ini</h4>
                    <p>Tambahkan buku baru atau edit buku yang ada untuk menggunakan kategori ini</p>
                    <a href="{{ route('admin.buku.create') }}" class="btn btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Tambah Buku
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
