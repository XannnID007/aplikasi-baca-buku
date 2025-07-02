@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('subtitle', 'Menambahkan kategori buku baru')

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 class="card-title">
                    <i class="fas fa-plus-circle"></i> Form Tambah Kategori
                </h3>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline"
                    style="background: #2d2d2d; border-color: #555; color: #e0e0e0;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('admin.kategori.store') }}" method="POST" id="kategoriForm">
            @csrf
            <div style="padding: 30px;">
                <div class="form-group">
                    <label class="form-label">Nama Kategori *</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                        placeholder="Masukkan nama kategori..." required>
                    @error('nama')
                        <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Kategori *</label>
                    <select name="jenis" class="form-control" required>
                        <option value="">Pilih Jenis</option>
                        <option value="fiksi" {{ old('jenis') == 'fiksi' ? 'selected' : '' }}>Fiksi</option>
                        <option value="non_fiksi" {{ old('jenis') == 'non_fiksi' ? 'selected' : '' }}>Non-Fiksi</option>
                    </select>
                    @error('jenis')
                        <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi kategori (opsional)...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div style="color: #dc3545; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 16px;"
                        id="submitBtn">
                        <i class="fas fa-save"></i> Simpan Kategori
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('kategoriForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;

            setTimeout(() => {
                if (submitBtn.disabled) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }, 5000);
        });
    </script>
@endsection
