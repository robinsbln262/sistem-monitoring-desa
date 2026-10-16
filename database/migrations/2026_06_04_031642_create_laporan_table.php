<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->foreignId('id_pembangunan')->constrained('pembangunan', 'id_pembangunan')->onDelete('cascade');
            $table->string('jenis_laporan', 50);
            $table->string('periode', 50);
            $table->date('tanggal_cetak');
            $table->foreignId('dibuat_oleh')->constrained('users', 'id_user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};