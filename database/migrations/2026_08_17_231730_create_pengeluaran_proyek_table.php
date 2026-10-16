<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_proyek', function (Blueprint $table) {
            $table->id('id_pengeluaran');

            $table->unsignedBigInteger('id_pembangunan');

            $table->enum('kategori', [
                'upah_tenaga_kerja',
                'material',
                'sewa_alat',
                'transportasi',
                'administrasi',
                'lainnya',
            ]);

            $table->string('uraian', 255);

            $table->decimal('volume', 15, 2)->nullable();

            $table->string('satuan', 50)->nullable();

            $table->decimal('harga_satuan', 15, 2)->default(0);

            $table->decimal('jumlah', 15, 2)->default(0);

            $table->date('tanggal_pengeluaran')->nullable();

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->foreign('id_pembangunan')
                ->references('id_pembangunan')
                ->on('proyek')
                ->cascadeOnDelete();

            $table->index([
                'id_pembangunan',
                'kategori'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_proyek');
    }
};