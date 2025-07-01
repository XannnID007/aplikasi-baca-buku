@extends('admin.layouts.app')

@section('title', 'Edit Buku')
@section('subtitle', 'Mengubah data buku: ' . $buku->judul)

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">
                    <i class="fas fa-edit"></i> Form Edit Buku
                </h3>
                <div>
                    <a href="{{ route('admin.buku.show', $buku) }}" class="btn btn-outline"
                        style="background: #17a2b8; border-color: #17a2b8; color: white; margin-right: 10px;">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    <a href="{{ route('admin.buku.index') }}" class="btn btn-outline"
                        style="background: #2d2d2d; border-color: #555; color: #e0e0e0;">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.buku.update', $buku) }}" method="POST" enctype="multipart/form-data"
            id="editBukuForm">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 300px; gap: 30px; padding: 30px;">
                <!-- Main Form -->
                <div>
                    <!-- Basic Information -->
                    <div style="margin-bottom: 40px;">
                        <h4
                            style="color: #fff; margin-bottom: 20px; border-bottom: 2px solid #007bff; padding-bottom: 10px;">
                            <i class="fas fa-info-circle"></i> Informasi Dasar
                        </h4>

                        <div class="form-group">
                            <label class="form-label">Judul Buku *</label>
                            <input type="text" name="judul" class="form-control"
                                value="{{ old('judul', $buku->judul) }}" placeholder="Masukkan judul buku..." required>
                            @error('judul')
                                <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Penulis *</label>
                            <input type="text" name="penulis" class="form-control"
                                value="{{ old('penulis', $buku->penulis) }}" placeholder="Nama penulis buku..." required>
                            @error('penulis')
                                <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi *</label>
                            <textarea name="deskripsi" class="form-control" rows="6" placeholder="Deskripsi singkat tentang buku ini..."
                                required>{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                            <div style="color: #888; font-size: 12px; margin-top: 5px;">
                                <span id="charCount">{{ strlen($buku->deskripsi) }}</span> karakter
                            </div>
                        </div>
                    </div>

                    <!-- Book Details -->
                    <div style="margin-bottom: 40px;">
                        <h4
                            style="color: #fff; margin-bottom: 20px; border-bottom: 2px solid #28a745; padding-bottom: 10px;">
                            <i class="fas fa-book"></i> Detail Buku
                        </h4>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">Jumlah Halaman *</label>
                                <input type="number" name="halaman" class="form-control"
                                    value="{{ old('halaman', $buku->halaman) }}" min="1" required>
                                @error('halaman')
                                    <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tahun Terbit *</label>
                                <input type="number" name="tahun_terbit" class="form-control"
                                    value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1900"
                                    max="{{ date('Y') }}" required>
                                @error('tahun_terbit')
                                    <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Jenis Buku *</label>
                                <select name="jenis" class="form-control" required id="jenisBuku">
                                    <option value="">Pilih Jenis</option>
                                    <option value="fiksi" {{ old('jenis', $buku->jenis) == 'fiksi' ? 'selected' : '' }}>
                                        Fiksi</option>
                                    <option value="non_fiksi"
                                        {{ old('jenis', $buku->jenis) == 'non_fiksi' ? 'selected' : '' }}>Non-Fiksi
                                    </option>
                                </select>
                                @error('jenis')
                                    <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div style="margin-bottom: 40px;">
                        <h4
                            style="color: #fff; margin-bottom: 20px; border-bottom: 2px solid #ffc107; padding-bottom: 10px;">
                            <i class="fas fa-tags"></i> Kategori Buku
                        </h4>

                        <div class="form-group">
                            <label class="form-label">Pilih Kategori *</label>
                            <div id="kategoriContainer">
                                <div class="kategori-grid">
                                    @foreach ($kategoris as $kategori)
                                        <div class="kategori-item {{ in_array($kategori->id, $selectedKategoris) ? 'selected' : '' }}"
                                            onclick="toggleKategori(this, {{ $kategori->id }})">
                                            <input type="checkbox" name="kategori_ids[]" value="{{ $kategori->id }}"
                                                {{ in_array($kategori->id, $selectedKategoris) ? 'checked' : '' }}>
                                            <div style="font-weight: 500; margin-bottom: 5px;">{{ $kategori->nama }}</div>
                                            <div style="font-size: 11px; color: #888;">{{ $kategori->deskripsi }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('kategori_ids')
                                <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- File Uploads -->
                    <div>
                        <h4
                            style="color: #fff; margin-bottom: 20px; border-bottom: 2px solid #dc3545; padding-bottom: 10px;">
                            <i class="fas fa-upload"></i> Update File
                        </h4>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">File Buku <span style="font-size: 12px; color: #888;">(Kosongkan
                                        jika tidak diubah)</span></label>
                                <div class="file-upload-area" onclick="document.getElementById('file_buku').click()">
                                    <input type="file" name="file_buku" id="file_buku" accept=".pdf,.epub"
                                        style="display: none;">
                                    <div id="fileBookPreview">
                                        <i class="fas fa-file-pdf"
                                            style="font-size: 32px; color: #dc3545; margin-bottom: 10px;"></i>
                                        <p style="color: #fff; margin: 0; font-weight: 500;">File saat ini:
                                            {{ basename($buku->file_path) }}</p>
                                        <p style="color: #888; font-size: 12px;">Klik untuk upload file baru</p>
                                    </div>
                                </div>
                                @error('file_buku')
                                    <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Cover Buku <span
                                        style="font-size: 12px; color: #888;">(Kosongkan jika tidak diubah)</span></label>
                                <div class="file-upload-area" onclick="document.getElementById('cover_gambar').click()">
                                    <input type="file" name="cover_gambar" id="cover_gambar" accept="image/*"
                                        style="display: none;">
                                    <div id="imageCoverPreview">
                                        @if ($buku->cover_gambar)
                                            <img src="{{ Storage::url($buku->cover_gambar) }}"
                                                style="width: 100px; height: 120px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;">
                                            <p style="color: #fff; margin: 0; font-size: 12px;">Klik untuk ganti cover</p>
                                        @else
                                            <i class="fas fa-image"
                                                style="font-size: 32px; color: #666; margin-bottom: 10px;"></i>
                                            <p style="color: #888; margin: 0;">Klik untuk upload cover</p>
                                        @endif
                                    </div>
                                </div>
                                @error('cover_gambar')
                                    <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div>
                    <!-- Current Book Info -->
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-header">
                            <h4 style="color: #fff; font-size: 16px;">
                                <i class="fas fa-info"></i> Info Buku Saat Ini
                            </h4>
                        </div>
                        <div style="padding: 20px; text-align: center;">
                            <div
                                style="width: 120px; height: 160px; background: #333; border-radius: 8px; margin: 0 auto 15px; overflow: hidden;">
                                @if ($buku->cover_gambar)
                                    <img src="{{ Storage::url($buku->cover_gambar) }}"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div
                                        style="display: flex; align-items: center; justify-content: center; height: 100%;">
                                        <i class="fas fa-book" style="font-size: 32px; color: #666;"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 style="color: #fff; margin-bottom: 5px;">{{ $buku->judul }}</h5>
                            <p style="color: #b0b0b0; font-size: 14px; margin-bottom: 10px;">{{ $buku->penulis }}</p>
                            <div style="font-size: 12px; color: #888;">
                                <div><i class="fas fa-eye"></i> {{ number_format($buku->views) }} views</div>
                                <div><i class="fas fa-star"></i> {{ number_format($buku->rating_rata_rata, 1) }}
                                    ({{ $buku->total_ratings }})</div>
                                <div><i class="fas fa-calendar"></i> Ditambahkan {{ $buku->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-header">
                            <h4 style="color: #fff; font-size: 16px;">
                                <i class="fas fa-chart-bar"></i> Statistik
                            </h4>
                        </div>
                        <div style="padding: 20px;">
                            <div style="margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="color: #b0b0b0; font-size: 12px;">Views</span>
                                    <span style="color: #fff; font-weight: 600;">{{ number_format($buku->views) }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="color: #b0b0b0; font-size: 12px;">Bookmarks</span>
                                    <span style="color: #fff; font-weight: 600;">{{ $buku->bookmarks->count() }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span style="color: #b0b0b0; font-size: 12px;">Pembaca</span>
                                    <span
                                        style="color: #fff; font-weight: 600;">{{ $buku->riwayatBacaans->count() }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #b0b0b0; font-size: 12px;">Rating</span>
                                    <span style="color: #fff; font-weight: 600;">{{ $buku->total_ratings }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <button type="submit" class="btn btn-warning"
                        style="width: 100%; padding: 15px; font-size: 16px; margin-bottom: 10px;" id="submitBtn">
                        <i class="fas fa-save"></i> Update Buku
                    </button>

                    <a href="{{ route('admin.buku.show', $buku) }}" class="btn btn-outline"
                        style="width: 100%; padding: 12px; background: #17a2b8; border-color: #17a2b8; color: white;">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </form>
    </div>

    <style>
        .file-upload-area {
            border: 2px dashed #333;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #1a1a1a;
        }

        .file-upload-area:hover {
            border-color: #007bff;
            background: #222;
        }

        .kategori-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .kategori-item {
            background: #2d2d2d;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }

        .kategori-item:hover {
            border-color: #007bff;
        }

        .kategori-item.selected {
            background: #007bff;
            border-color: #007bff;
            color: white;
        }

        .kategori-item input[type="checkbox"] {
            display: none;
        }
    </style>

    <script>
        // Character counter
        const deskripsiTextarea = document.querySelector('textarea[name="deskripsi"]');
        const charCount = document.getElementById('charCount');

        if (deskripsiTextarea) {
            deskripsiTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }

        // Toggle kategori
        function toggleKategori(element, id) {
            const checkbox = element.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            element.classList.toggle('selected', checkbox.checked);
        }

        // File upload previews
        document.getElementById('file_buku').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('fileBookPreview');

            if (file) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                preview.innerHTML = `
            <i class="fas fa-file-pdf" style="font-size: 32px; color: #dc3545; margin-bottom: 10px;"></i>
            <p style="color: #fff; margin: 0; font-weight: 500;">File baru: ${file.name}</p>
            <p style="color: #888; font-size: 12px;">${fileSize} MB</p>
        `;
            }
        });

        document.getElementById('cover_gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imageCoverPreview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                <img src="${e.target.result}" style="width: 100px; height: 120px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;">
                <p style="color: #fff; margin: 0; font-size: 12px;">Cover baru: ${file.name}</p>
            `;
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission
        document.getElementById('editBukuForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;

            // Restore button after delay if submission fails
            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 5000);
        });
    </script>
@endsection
