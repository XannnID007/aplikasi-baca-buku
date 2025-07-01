@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan data sistem perpustakaan digital')

@section('content')
    <div class="stats-grid">
        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stats-number">{{ $totalBuku }}</div>
            <div class="stats-label">Total Buku</div>
        </div>

        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-number">{{ $totalUser }}</div>
            <div class="stats-label">Total User</div>
        </div>

        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stats-number">{{ $totalKategori }}</div>
            <div class="stats-label">Total Kategori</div>
        </div>

        <div class="stats-card">
            <div class="stats-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stats-number">{{ $bukuPopuler->sum('views') }}</div>
            <div class="stats-label">Total Views</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <!-- Buku Populer -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-fire"></i> Buku Populer
                </h3>
            </div>
            <div class="card-body">
                @if ($bukuPopuler->count() > 0)
                    <div style="overflow-x: auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Views</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bukuPopuler as $buku)
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($buku->judul, 30) }}</strong>
                                        </td>
                                        <td>{{ $buku->penulis }}</td>
                                        <td>
                                            <span class="badge"
                                                style="background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px;">
                                                {{ number_format($buku->views) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center;">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($buku->rating_rata_rata))
                                                        <i class="fas fa-star" style="color: #ffc107; font-size: 12px;"></i>
                                                    @elseif($i - 0.5 <= $buku->rating_rata_rata)
                                                        <i class="fas fa-star-half-alt"
                                                            style="color: #ffc107; font-size: 12px;"></i>
                                                    @else
                                                        <i class="far fa-star" style="color: #666; font-size: 12px;"></i>
                                                    @endif
                                                @endfor
                                                <span style="margin-left: 5px; font-size: 12px; color: #b0b0b0;">
                                                    ({{ number_format($buku->rating_rata_rata, 1) }})
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="fas fa-book" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>Belum ada data buku</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock"></i> Aktivitas Terbaru
                </h3>
            </div>
            <div class="card-body">
                <div class="activity-feed">
                    <div class="activity-item"
                        style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #333;">
                        <div class="activity-icon"
                            style="width: 40px; height: 40px; background: #28a745; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <i class="fas fa-plus" style="color: white; font-size: 14px;"></i>
                        </div>
                        <div class="activity-content">
                            <div style="color: #fff; font-weight: 500;">Buku baru ditambahkan</div>
                            <div style="color: #b0b0b0; font-size: 12px;">2 jam yang lalu</div>
                        </div>
                    </div>

                    <div class="activity-item"
                        style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #333;">
                        <div class="activity-icon"
                            style="width: 40px; height: 40px; background: #007bff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <i class="fas fa-user-plus" style="color: white; font-size: 14px;"></i>
                        </div>
                        <div class="activity-content">
                            <div style="color: #fff; font-weight: 500;">User baru mendaftar</div>
                            <div style="color: #b0b0b0; font-size: 12px;">5 jam yang lalu</div>
                        </div>
                    </div>

                    <div class="activity-item"
                        style="display: flex; align-items: center; padding: 15px 0; border-bottom: 1px solid #333;">
                        <div class="activity-icon"
                            style="width: 40px; height: 40px; background: #ffc107; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <i class="fas fa-star" style="color: #212529; font-size: 14px;"></i>
                        </div>
                        <div class="activity-content">
                            <div style="color: #fff; font-weight: 500;">Rating baru diberikan</div>
                            <div style="color: #b0b0b0; font-size: 12px;">1 hari yang lalu</div>
                        </div>
                    </div>

                    <div class="activity-item" style="display: flex; align-items: center; padding: 15px 0;">
                        <div class="activity-icon"
                            style="width: 40px; height: 40px; background: #6c757d; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <i class="fas fa-tags" style="color: white; font-size: 14px;"></i>
                        </div>
                        <div class="activity-content">
                            <div style="color: #fff; font-weight: 500;">Kategori baru dibuat</div>
                            <div style="color: #b0b0b0; font-size: 12px;">2 hari yang lalu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-line"></i> Statistik Bulanan
            </h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div
                    style="text-align: center; padding: 20px; background: rgba(0,123,255,0.1); border-radius: 8px; border: 1px solid rgba(0,123,255,0.3);">
                    <i class="fas fa-book-open" style="font-size: 32px; color: #007bff; margin-bottom: 10px;"></i>
                    <div style="font-size: 24px; font-weight: bold; color: #fff;">{{ $totalBuku }}</div>
                    <div style="color: #b0b0b0; font-size: 14px;">Buku Tersedia</div>
                </div>

                <div
                    style="text-align: center; padding: 20px; background: rgba(40,167,69,0.1); border-radius: 8px; border: 1px solid rgba(40,167,69,0.3);">
                    <i class="fas fa-users" style="font-size: 32px; color: #28a745; margin-bottom: 10px;"></i>
                    <div style="font-size: 24px; font-weight: bold; color: #fff;">{{ $totalUser }}</div>
                    <div style="color: #b0b0b0; font-size: 14px;">Pengguna Aktif</div>
                </div>

                <div
                    style="text-align: center; padding: 20px; background: rgba(255,193,7,0.1); border-radius: 8px; border: 1px solid rgba(255,193,7,0.3);">
                    <i class="fas fa-eye" style="font-size: 32px; color: #ffc107; margin-bottom: 10px;"></i>
                    <div style="font-size: 24px; font-weight: bold; color: #fff;">{{ $bukuPopuler->sum('views') }}</div>
                    <div style="color: #b0b0b0; font-size: 14px;">Total Views</div>
                </div>

                <div
                    style="text-align: center; padding: 20px; background: rgba(220,53,69,0.1); border-radius: 8px; border: 1px solid rgba(220,53,69,0.3);">
                    <i class="fas fa-download" style="font-size: 32px; color: #dc3545; margin-bottom: 10px;"></i>
                    <div style="font-size: 24px; font-weight: bold; color: #fff;">1.2K</div>
                    <div style="color: #b0b0b0; font-size: 14px;">Total Download</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-bolt"></i> Aksi Cepat
            </h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="{{ route('admin.buku.create') }}" class="btn btn-primary"
                    style="text-align: center; padding: 20px; text-decoration: none;">
                    <i class="fas fa-plus-circle" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    Tambah Buku Baru
                </a>

                <a href="{{ route('admin.kategori.create') }}" class="btn btn-success"
                    style="text-align: center; padding: 20px; text-decoration: none;">
                    <i class="fas fa-tags" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    Tambah Kategori
                </a>

                <a href="{{ route('admin.user.index') }}" class="btn btn-warning"
                    style="text-align: center; padding: 20px; text-decoration: none;">
                    <i class="fas fa-users-cog" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    Kelola Pengguna
                </a>

                <a href="{{ route('admin.laporan.buku') }}" class="btn btn-info"
                    style="text-align: center; padding: 20px; text-decoration: none; background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                    <i class="fas fa-chart-bar" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                    Lihat Laporan
                </a>
            </div>
        </div>
    </div>
@endsection
