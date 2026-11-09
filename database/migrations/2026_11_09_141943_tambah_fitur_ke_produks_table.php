<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('produks', function (Blueprint $table) {
        $table->string('jenis_bunga')->nullable()->after('kategori');
        $table->string('warna')->nullable()->after('jenis_bunga');
        $table->string('acara')->nullable()->after('warna');
    });
}

public function down(): void
{
    Schema::table('produks', function (Blueprint $table) {
        $table->dropColumn(['jenis_bunga', 'warna', 'acara']);
    });
}
};
