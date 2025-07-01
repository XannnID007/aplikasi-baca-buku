@extends('admin.layouts.app')

@section('title', 'Tambah Buku')
@section('subtitle', 'Menambahkan buku baru ke dalam perpustakaan digital')

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">
                    <i class="fas fa-plus-circle"></i> Form Tambah Buku
                </h3>
                <a href="{{ route('admin.buku.index') }}" class="btn btn-outline"
                    style="background: #2d2d2d; border-color: #555; color: #e0e0e0;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('admin.buku.store') }}" method="POST" enctype="multipart/form-data" id="bukuForm">
            @csrf

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
                            <input type="text" name="judul" class="form-control" value="{{ old('judul') }}"
                                placeholder="Masukkan judul buku..." required>
                            @error('judul')
                                <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Penulis *</label>
                            <input type="text" name="penulis" class="form-control" value="{{ old('penulis') }}"
                                placeholder="Nama penulis buku..." required>
                            @error('penulis')
                                <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Deskripsi *</label>
                            <textarea name="deskripsi" class="form-control" rows="6" placeholder="Deskripsi singkat tentang buku ini..."
                                required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                            @enderror
                            <div style="color: #888; font-size: 12px; margin-top: 5px;">
                                <span id="charCount">0</span> karakter (minimal 50 karakter)
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
                                <input type="number" name="halaman" class="form-control" value="{{ old('halaman') }}"
                                    min="1" placeholder="0" required>
                                @error('halaman')
                                    <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tahun Terbit *</label>
                                <input type="number" name="tahun_terbit" class="form-control"
                                    value="{{ old('tahun_terbit', date('Y')) }}" min="1900" max="{{ date('Y') }}"
                                    required>
                                @error('tahun_terbit')
                                    <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Jenis Buku *</label>
                                <select name="jenis" class="form-control" required id="jenisBuku">
                                    <option value="">Pilih Jenis</option>
                                    <option value="fiksi" {{ old('jenis') == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
                                    <option value="non_fiksi" {{ old('jenis') == 'non_fiksi' ? 'selected' : '' }}>Non-Fiksi
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
                            <label class="form-label">Pilih Kategori * <span style="font-size: 12px; color: #888;">(minimal
                                    1 kategori)</span></label>
                            <div id="kategoriContainer">
                                <div style="color: #888; text-align: center; padding: 20px;">
                                    Pilih jenis buku terlebih dahulu untuk melihat kategori yang tersedia
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
                            <i class="fas fa-upload"></i> Upload File
                        </h4>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label class="form-label">File Buku * <span style="font-size: 12px; color: #888;">(PDF/EPUB,
                                        max 10MB)</span></label>
                                <div class="file-upload-area" onclick="document.getElementById('file_buku').click()">
                                    <input type="file" name="file_buku" id="file_buku" accept=".pdf,.epub"
                                        style="display: none;" required>
                                    <div id="fileBookPreview">
                                        <i class="fas fa-cloud-upload-alt"
                                            style="font-size: 32px; color: #666; margin-bottom: 10px;"></i>
                                        <p style="color: #888; margin: 0;">Klik untuk upload file buku</p>
                                        <p style="color: #666; font-size: 12px;">PDF atau EPUB</p>
                                    </div>
                                </div>
                                @error('file_buku')
                                    <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Cover Buku <span
                                        style="font-size: 12px; color: #888;">(opsional, JPG/PNG, max 2MB)</span></label>
                                <div class="file-upload-area" onclick="document.getElementById('cover_gambar').click()">
                                    <input type="file" name="cover_gambar" id="cover_gambar" accept="image/*"
                                        style="display: none;">
                                    <div id="imageCoverPreview">
                                        <i class="fas fa-image"
                                            style="font-size: 32px; color: #666; margin-bottom: 10px;"></i>
                                        <p style="color: #888; margin: 0;">Klik untuk upload cover</p>
                                        <p style="color: #666; font-size: 12px;">JPG, PNG, GIF</p>
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
                    <!-- Preview Card -->
                    <div class="card" style="margin-bottom: 20px;">
                        <div class="card-header">
                            <h4 style="color: #fff; font-size: 16px;">
                                <i class="fas fa-eye"></i> Preview Buku
                            </h4>
                        </div>
                        <div style="padding: 20px; text-align: center;">
                            <div id="previewCover"
                                style="width: 120px; height: 160px; background: #333; border-radius: 8px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-book" style="font-size: 32px; color: #666;"></i>
                            </div>
                            <h5 id="previewTitle" style="color: #fff; margin-bottom: 5px;">Judul Buku</h5>
                            <p id="previewAuthor" style="color: #b0b0b0; font-size: 14px;">Nama Penulis</p>
                            <div id="previewMeta" style="font-size: 12px; color: #888;">
                                <div><i class="fas fa-calendar"></i> <span id="previewYear">-</span></div>
                                <div><i class="fas fa-file-alt"></i> <span id="previewPages">-</span> hal</div>
                                <div><i class="fas fa-tag"></i> <span id="previewType">-</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Guidelines -->
                    <div class="card">
                        <div class="card-header">
                            <h4 style="color: #fff; font-size: 16px;">
                                <i class="fas fa-lightbulb"></i> Panduan
                            </h4>
                        </div>
                        <div style="padding: 20px;">
                            <div style="margin-bottom: 15px;">
                                <h6 style="color: #fff; margin-bottom: 5px;">Format File:</h6>
                                <ul style="color: #b0b0b0; font-size: 12px; margin: 0; padding-left: 20px;">
                                    <li>Buku: PDF atau EPUB</li>
                                    <li>Cover: JPG, PNG, GIF</li>
                                </ul>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <h6 style="color: #fff; margin-bottom: 5px;">Ukuran Maksimal:</h6>
                                <ul style="color: #b0b0b0; font-size: 12px; margin: 0; padding-left: 20px;">
                                    <li>File buku: 10MB</li>
                                    <li>Cover: 2MB</li>
                                </ul>
                            </div>

                            <div>
                                <h6 style="color: #fff; margin-bottom: 5px;">Tips:</h6>
                                <ul style="color: #b0b0b0; font-size: 12px; margin: 0; padding-left: 20px;">
                                    <li>Gunakan judul yang jelas</li>
                                    <li>Deskripsi minimal 50 karakter</li>
                                    <li>Pilih kategori yang sesuai</li>
                                    <li>Cover membantu daya tarik</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success"
                        style="width: 100%; padding: 15px; font-size: 16px; margin-top: 20px;" id="submitBtn">
                        <i class="fas fa-save"></i> Simpan Buku
                    </button>
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

        .file-upload-area.dragover {
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
        // Character counter for description
        const deskripsiTextarea = document.querySelector('textarea[name="deskripsi"]');
        const charCount = document.getElementById('charCount');

        if (deskripsiTextarea) {
            deskripsiTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                if (this.value.length < 50) {
                    charCount.style.color = '#dc3545';
                } else {
                    charCount.style.color = '#28a745';
                }
            });
        }

        // Load categories based on jenis selection
        const jenisBuku = document.getElementById('jenisBuku');
        const kategoriContainer = document.getElementById('kategoriContainer');

        jenisBuku.addEventListener('change', function() {
            if (this.value) {
                loadKategoris(this.value);
            } else {
                kategoriContainer.innerHTML =
                    '<div style="color: #888; text-align: center; padding: 20px;">Pilih jenis buku terlebih dahulu</div>';
            }
        });

        function loadKategoris(jenis) {
            kategoriContainer.innerHTML =
                '<div style="color: #888; text-align: center; padding: 20px;">Memuat kategori...</div>';

            fetch(`/admin/kategori/by-jenis/${jenis}`)
                .then(response => response.json())
                .then(kategoris => {
                    if (kategoris.length > 0) {
                        let html = '<div class="kategori-grid">';
                        kategoris.forEach(kategori => {
                            const isSelected = {!! json_encode(old('kategori_ids', [])) !!}.includes(kategori.id);
                            html += `
                        <div class="kategori-item ${isSelected ? 'selected' : ''}" onclick="toggleKategori(this, ${kategori.id})">
                            <input type="checkbox" name="kategori_ids[]" value="${kategori.id}" ${isSelected ? 'checked' : ''}>
                            <div style="font-weight: 500; margin-bottom: 5px;">${kategori.nama}</div>
                            <div style="font-size: 11px; color: #888;">${kategori.deskripsi || ''}</div>
                        </div>
                    `;
                        });
                        html += '</div>';
                        kategoriContainer.innerHTML = html;
                    } else {
                        kategoriContainer.innerHTML =
                            '<div style="color: #888; text-align: center; padding: 20px;">Tidak ada kategori untuk jenis ini</div>';
                    }
                })
                .catch(error => {
                    kategoriContainer.innerHTML =
                        '<div style="color: #dc3545; text-align: center; padding: 20px;">Error memuat kategori</div>';
                });
        }

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
            <p style="color: #fff; margin: 0; font-weight: 500;">${file.name}</p>
            <p style="color: #888; font-size: 12px;">${fileSize} MB</p>
        `;
            }
        });

        document.getElementById('cover_gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imageCoverPreview');
            const previewCover = document.getElementById('previewCover');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `
                <img src="${e.target.result}" style="width: 100px; height: 120px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;">
                <p style="color: #fff; margin: 0; font-size: 12px;">${file.name}</p>
            `;

                    previewCover.innerHTML =
                        `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Real-time preview updates
        function updatePreview() {
            const judul = document.querySelector('input[name="judul"]').value || 'Judul Buku';
            const penulis = document.querySelector('input[name="penulis"]').value || 'Nama Penulis';
            const tahun = document.querySelector('input[name="tahun_terbit"]').value || '-';
            const halaman = document.querySelector('input[name="halaman"]').value || '-';
            const jenis = document.querySelector('select[name="jenis"]').value || '-';

            document.getElementById('previewTitle').textContent = judul;
            document.getElementById('previewAuthor').textContent = penulis;
            document.getElementById('previewYear').textContent = tahun;
            document.getElementById('previewPages').textContent = halaman;
            document.getElementById('previewType').textContent = jenis === 'fiksi' ? 'Fiksi' : jenis === 'non_fiksi' ?
                'Non-Fiksi' : '-';
        }

        // Add event listeners for real-time preview
        document.querySelectorAll(
            'input[name="judul"], input[name="penulis"], input[name="tahun_terbit"], input[name="halaman"], select[name="jenis"]'
            ).forEach(input => {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        });

        // Form validation
        document.getElementById('bukuForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            let firstErrorField = null;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                    if (!firstErrorField) firstErrorField = field;
                } else {
                    field.style.borderColor = '#333';
                }
            });

            // Check if at least one category is selected
            const selectedKategoris = document.querySelectorAll('input[name="kategori_ids[]"]:checked');
            if (selectedKategoris.length === 0) {
                isValid = false;
                alert('Silakan pilih minimal satu kategori');
            }

            // Check description length
            const deskripsi = document.querySelector('textarea[name="deskripsi"]').value;
            if (deskripsi.length < 50) {
                isValid = false;
                alert('Deskripsi minimal 50 karakter');
                document.querySelector('textarea[name="deskripsi"]').focus();
            }

            if (!isValid) {
                e.preventDefault();
                if (firstErrorField) {
                    firstErrorField.focus();
                }
                return false;
            }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;
        });

        // Drag and drop for file uploads
        ['file_buku', 'cover_gambar'].forEach(inputId => {
            const input = document.getElementById(inputId);
            const area = input.closest('.file-upload-area');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                area.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                area.addEventListener(eventName, () => area.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                area.addEventListener(eventName, () => area.classList.remove('dragover'), false);
            });

            area.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                input.files = files;
                input.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            }
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Load initial categories if jenis is already selected
        if (jenisBuku.value) {
            loadKategoris(jenisBuku.value);
        }

        // Initial preview update
        updatePreview();

        // Auto-save to localStorage
        function saveToLocalStorage() {
            const formData = new FormData(document.getElementById('bukuForm'));
            const data = {};

            for (let [key, value] of formData.entries()) {
                if (key !== 'file_buku' && key !== 'cover_gambar') {
                    if (data[key]) {
                        if (Array.isArray(data[key])) {
                            data[key].push(value);
                        } else {
                            data[key] = [data[key], value];
                        }
                    } else {
                        data[key] = value;
                    }
                }
            }

            localStorage.setItem('bukuFormDraft', JSON.stringify(data));
        }

        // Load from localStorage
        function loadFromLocalStorage() {
            const saved = localStorage.getItem('bukuFormDraft');
            if (saved) {
                const data = JSON.parse(saved);

                Object.keys(data).forEach(key => {
                    const field = document.querySelector(`[name="${key}"]`);
                    if (field && !field.value) {
                        if (Array.isArray(data[key])) {
                            data[key].forEach(value => {
                                const checkbox = document.querySelector(
                                `[name="${key}"][value="${value}"]`);
                                if (checkbox) checkbox.checked = true;
                            });
                        } else {
                            field.value = data[key];
                        }
                    }
                });

                updatePreview();
            }
        }

        // Auto-save every 30 seconds
        setInterval(saveToLocalStorage, 30000);

        // Clear localStorage on successful submit
        document.getElementById('bukuForm').addEventListener('submit', function() {
            localStorage.removeItem('bukuFormDraft');
        });

        // Ask user if they want to restore draft
        window.addEventListener('load', function() {
            const saved = localStorage.getItem('bukuFormDraft');
            if (saved && confirm('Ditemukan draft form yang belum tersimpan. Apakah Anda ingin memulihkannya?')) {
                loadFromLocalStorage();
            }
        });
    </script>
@endsection
