<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komentar_masyarakat', function (Blueprint $table) {
            $table->id('id_komentar');
            $table->unsignedBigInteger('id_pembangunan');
            $table->string('nama', 100);
            $table->string('email', 100)->nullable();
            $table->text('komentar');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('tanggal_komentar')->useCurrent();
            $table->timestamps();

            $table->foreign('id_pembangunan')
                ->references('id_pembangunan')
                ->on('proyek')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar_masyarakat');
    }
};
