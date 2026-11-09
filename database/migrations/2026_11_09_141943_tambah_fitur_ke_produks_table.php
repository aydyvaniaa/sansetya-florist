<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migration awal project saat ini sudah mendefinisikan kolom-kolom ini.
        // Pemeriksaan diperlukan untuk database lama yang belum memilikinya.
        if (!Schema::hasColumn('produks', 'jenis_bunga')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->string('jenis_bunga')->nullable();
            });
        }

        if (!Schema::hasColumn('produks', 'warna')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->string('warna')->nullable();
            });
        }

        if (!Schema::hasColumn('produks', 'acara')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->string('acara')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Kolom-kolom ini juga didefinisikan oleh migration awal.
        // Tidak dihapus saat rollback agar data produk tidak hilang.
    }
};
