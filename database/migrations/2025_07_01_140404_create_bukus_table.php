<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->text('deskripsi');
            $table->string('cover_gambar')->nullable();
            $table->string('file_path'); // Path to book file
            $table->integer('halaman')->default(0);
            $table->integer('tahun_terbit');
            $table->string('jenis'); // 'fiksi' or 'non_fiksi'
            $table->integer('views')->default(0);
            $table->decimal('rating_rata_rata', 3, 2)->default(0);
            $table->integer('total_ratings')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
