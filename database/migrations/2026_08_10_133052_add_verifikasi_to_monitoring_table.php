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
        Schema::table('monitoring', function (Blueprint $table) {
            // Tambah field status_verifikasi dengan default 'pending'
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('komentar_admin')
                ->comment('Status verifikasi progres: pending, approved, rejected');

            // Tambah field catatan_verifikasi untuk catatan penolakan/persetujuan
            $table->text('catatan_verifikasi')
                ->nullable()
                ->after('status_verifikasi')
                ->comment('Catatan dari admin saat verifikasi (terutama jika ditolak)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'catatan_verifikasi']);
        });
    }
};
