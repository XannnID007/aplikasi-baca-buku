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
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->integer('halaman')->nullable()->after('buku_id')->comment('Halaman yang dibookmark');
            $table->text('note')->nullable()->after('halaman')->comment('Catatan bookmark');

            // Add index for better performance
            $table->index(['user_id', 'buku_id', 'halaman']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'buku_id', 'halaman']);
            $table->dropColumn(['halaman', 'note']);
        });
    }
};
