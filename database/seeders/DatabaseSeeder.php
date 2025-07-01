<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Create sample user
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        // Create categories for fiction
        $kategoriFiksi = [
            ['nama' => 'Romantis', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita cinta dan romantika'],
            ['nama' => 'Horor', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita menakutkan dan misterius'],
            ['nama' => 'Petualangan', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita petualangan dan penjelajahan'],
            ['nama' => 'Fantasi', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita dunia fantasi dan sihir'],
            ['nama' => 'Sci-Fi', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita fiksi ilmiah'],
            ['nama' => 'Thriller', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita menegangkan dan penuh aksi'],
            ['nama' => 'Komedi', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita lucu dan menghibur'],
            ['nama' => 'Drama', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita dramatis dan emosional'],
            ['nama' => 'Misteri', 'jenis' => 'fiksi', 'deskripsi' => 'Cerita misteri dan detektif'],
        ];

        // Create categories for non-fiction
        $kategoriNonFiksi = [
            ['nama' => 'Pendidikan', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku pembelajaran dan edukasi'],
            ['nama' => 'Teknologi', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku tentang teknologi dan programming'],
            ['nama' => 'Biografi', 'jenis' => 'non_fiksi', 'deskripsi' => 'Kisah hidup tokoh terkenal'],
            ['nama' => 'Sains', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku ilmu pengetahuan dan penelitian'],
            ['nama' => 'Sejarah', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku sejarah dan peradaban'],
            ['nama' => 'Kesehatan', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku kesehatan dan medical'],
            ['nama' => 'Bisnis', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku bisnis dan entrepreneurship'],
            ['nama' => 'Psikologi', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku psikologi dan mental health'],
            ['nama' => 'Agama', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku keagamaan dan spiritual'],
            ['nama' => 'Seni', 'jenis' => 'non_fiksi', 'deskripsi' => 'Buku seni dan budaya'],
        ];

        foreach (array_merge($kategoriFiksi, $kategoriNonFiksi) as $kategori) {
            Kategori::create($kategori);
        }

        // Create sample books
        $sampleBooks = [
            // Fiction books
            [
                'judul' => 'Pride and Prejudice',
                'penulis' => 'Jane Austen',
                'deskripsi' => 'Classic romantic novel about Elizabeth Bennet and Mr. Darcy',
                'halaman' => 432,
                'tahun_terbit' => 1813,
                'jenis' => 'fiksi',
                'file_path' => 'books/pride-prejudice.pdf',
                'views' => 1250,
                'rating_rata_rata' => 4.5,
                'total_ratings' => 89,
                'kategoris' => ['Romantis', 'Drama']
            ],
            [
                'judul' => 'Frankenstein',
                'penulis' => 'Mary Shelley',
                'deskripsi' => 'Gothic novel about Victor Frankenstein and his creature',
                'halaman' => 280,
                'tahun_terbit' => 1818,
                'jenis' => 'fiksi',
                'file_path' => 'books/frankenstein.pdf',
                'views' => 890,
                'rating_rata_rata' => 4.2,
                'total_ratings' => 67,
                'kategoris' => ['Horor', 'Sci-Fi']
            ],
            [
                'judul' => 'The Time Machine',
                'penulis' => 'H.G. Wells',
                'deskripsi' => 'Science fiction novel about time travel',
                'halaman' => 118,
                'tahun_terbit' => 1895,
                'jenis' => 'fiksi',
                'file_path' => 'books/time-machine.pdf',
                'views' => 756,
                'rating_rata_rata' => 4.0,
                'total_ratings' => 45,
                'kategoris' => ['Sci-Fi', 'Petualangan']
            ],
            [
                'judul' => 'Alice\'s Adventures in Wonderland',
                'penulis' => 'Lewis Carroll',
                'deskripsi' => 'Fantasy novel about Alice falling down a rabbit hole',
                'halaman' => 200,
                'tahun_terbit' => 1865,
                'jenis' => 'fiksi',
                'file_path' => 'books/alice-wonderland.pdf',
                'views' => 1120,
                'rating_rata_rata' => 4.3,
                'total_ratings' => 78,
                'kategoris' => ['Fantasi', 'Petualangan']
            ],

            // Non-fiction books
            [
                'judul' => 'The Origin of Species',
                'penulis' => 'Charles Darwin',
                'deskripsi' => 'Scientific work on evolution by natural selection',
                'halaman' => 502,
                'tahun_terbit' => 1859,
                'jenis' => 'non_fiksi',
                'file_path' => 'books/origin-species.pdf',
                'views' => 834,
                'rating_rata_rata' => 4.4,
                'total_ratings' => 56,
                'kategoris' => ['Sains', 'Pendidikan']
            ],
            [
                'judul' => 'Relativity: The Special and General Theory',
                'penulis' => 'Albert Einstein',
                'deskripsi' => 'Einstein\'s explanation of relativity theory',
                'halaman' => 168,
                'tahun_terbit' => 1916,
                'jenis' => 'non_fiksi',
                'file_path' => 'books/relativity.pdf',
                'views' => 667,
                'rating_rata_rata' => 4.1,
                'total_ratings' => 34,
                'kategoris' => ['Sains', 'Teknologi']
            ],
            [
                'judul' => 'The Autobiography of Benjamin Franklin',
                'penulis' => 'Benjamin Franklin',
                'deskripsi' => 'Memoir of one of America\'s founding fathers',
                'halaman' => 289,
                'tahun_terbit' => 1791,
                'jenis' => 'non_fiksi',
                'file_path' => 'books/franklin-auto.pdf',
                'views' => 445,
                'rating_rata_rata' => 3.9,
                'total_ratings' => 28,
                'kategoris' => ['Biografi', 'Sejarah']
            ],
        ];

        foreach ($sampleBooks as $bookData) {
            $kategoris = $bookData['kategoris'];
            unset($bookData['kategoris']);

            $buku = Buku::create($bookData);

            // Attach categories
            $kategoriIds = Kategori::whereIn('nama', $kategoris)->pluck('id');
            $buku->kategoris()->attach($kategoriIds);
        }
    }
}
