<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_user', 100);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('level', ['admin', 'perangkat_desa', 'kepala_desa'])->default('perangkat_desa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};