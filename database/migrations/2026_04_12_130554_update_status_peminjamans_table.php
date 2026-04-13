<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu',
                'dipinjam',
                'dikembalikan',
                'ditolak',
                'menunggu_verifikasi',
                'perlu_bayar_denda'
            ])->default('menunggu')->change();
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu', 'dipinjam', 'dikembalikan', 'ditolak'
            ])->default('menunggu')->change();
        });
    }
};