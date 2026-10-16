<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->id('id_evaluasi');
            $table->foreignId('id_pembangunan')->constrained('pembangunan', 'id_pembangunan')->onDelete('cascade');
            $table->date('tanggal_evaluasi');
            $table->text('hasil_evaluasi');
            $table->text('kendala')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi');
    }
};