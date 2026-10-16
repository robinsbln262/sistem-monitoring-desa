<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id('id_dokumentasi');
            $table->foreignId('id_pembangunan')->constrained('pembangunan', 'id_pembangunan')->onDelete('cascade');
            $table->string('group_id', 50)->nullable(); // HAPUS "after"
            $table->string('foto', 255);
            $table->text('keterangan')->nullable();
            $table->date('tanggal_upload');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi');
    }
};