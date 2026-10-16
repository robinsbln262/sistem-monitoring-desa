<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembangunan', function (Blueprint $table) {
            // Langsung tambah kolom mandor (string)
            $table->string('mandor', 100)->nullable()->after('id_user');
        });
    }

    public function down(): void
    {
        Schema::table('pembangunan', function (Blueprint $table) {
            $table->dropColumn('mandor');
        });
    }
};