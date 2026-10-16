<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->decimal('panjang', 15, 2)
                ->nullable()
                ->after('anggaran');

            $table->decimal('lebar', 15, 2)
                ->nullable()
                ->after('panjang');

            $table->decimal('ketebalan_tinggi', 15, 2)
                ->nullable()
                ->after('lebar');
        });
    }

    public function down(): void
    {
        Schema::table('proyek', function (Blueprint $table) {
            $table->dropColumn([
                'panjang',
                'lebar',
                'ketebalan_tinggi',
            ]);
        });
    }
};