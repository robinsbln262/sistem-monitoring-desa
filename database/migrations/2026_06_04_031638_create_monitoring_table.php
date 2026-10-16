<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring', function (Blueprint $table) {
            $table->id('id_monitoring');
            $table->foreignId('id_pembangunan')->constrained('pembangunan', 'id_pembangunan')->onDelete('cascade');
            $table->date('tanggal_monitoring');
            $table->integer('progres')->default(0);
            $table->string('status_progres', 50);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring');
    }
};