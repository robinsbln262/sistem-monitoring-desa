<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_rekanan', function (Blueprint $table) {
            $table->id('id_evaluasi_rekanan');
            $table->unsignedBigInteger('id_pembangunan');
            $table->unsignedBigInteger('id_rekanan');

            // ==================== WAKTU ====================
            $table->date('tanggal_evaluasi');
            $table->boolean('selesai_tepat_waktu')->default(false);
            $table->integer('keterlambatan_hari')->default(0);

            // ==================== KENDALA ====================
            $table->text('kendala')->nullable();

            // ==================== HASIL AKHIR ====================
            $table->text('hasil_akhir')->nullable();
            $table->enum('kualitas_pekerjaan', ['sangat_baik', 'baik', 'cukup', 'kurang'])->default('baik');
            $table->integer('persentase_penyelesaian')->default(100);

            // ==================== PENILAIAN ====================
            $table->integer('rating')->default(5); // 1-5
            $table->text('rekomendasi_rekanan')->nullable();

            // ==================== VERIFIKASI ====================
            $table->enum('status_verifikasi', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->unsignedBigInteger('diverifikasi_oleh')->nullable();
            $table->timestamp('tanggal_verifikasi')->nullable();

            $table->timestamps();

            // ==================== FOREIGN KEY ====================
            $table->foreign('id_pembangunan')
                ->references('id_pembangunan')
                ->on('proyek')
                ->onDelete('cascade');

            $table->foreign('id_rekanan')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('diverifikasi_oleh')
                ->references('id_user')
                ->on('users')
                ->onDelete('set null');

            // ==================== UNIQUE ====================
            $table->unique(['id_pembangunan', 'id_rekanan'], 'unique_evaluasi_rekanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_rekanan');
    }
};
