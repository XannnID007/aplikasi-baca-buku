<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BukuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'halaman' => 'required|integer|min:1',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'jenis' => 'required|in:fiksi,non_fiksi',
            'kategori_ids' => 'required|array|min:1',
            'kategori_ids.*' => 'exists:kategoris,id',
            'cover_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($this->isMethod('post')) {
            $rules['file_buku'] = 'required|file|mimes:pdf,epub|max:10240';
        } else {
            $rules['file_buku'] = 'nullable|file|mimes:pdf,epub|max:10240';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul buku harus diisi',
            'penulis.required' => 'Nama penulis harus diisi',
            'file_buku.required' => 'File buku harus diupload',
            'file_buku.mimes' => 'File buku harus berformat PDF atau EPUB',
            'cover_gambar.image' => 'Cover harus berupa gambar',
            'kategori_ids.required' => 'Minimal pilih satu kategori',
        ];
    }
}
