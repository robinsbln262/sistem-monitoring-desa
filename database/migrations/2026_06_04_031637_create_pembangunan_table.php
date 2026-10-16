<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembangunan', function (Blueprint $table) {
            $table->id('id_pembangunan');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('nama_pembangunan', 150);
            $table->string('lokasi', 150);
            $table->year('tahun_anggaran');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('sumber_dana', 100);
            $table->decimal('anggaran', 15, 2);
            $table->enum('status', ['direncanakan', 'berjalan', 'selesai'])->default('direncanakan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembangunan');
    }
};