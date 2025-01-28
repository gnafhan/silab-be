<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->enum('semester', ["Genap", "Ganjil"]);
            $table->string('year');
            $table->enum('description', ["Tugas Akhir", "Yudisium"]);
            $table->enum('status', ["Aktif", "Tidak Aktif"]);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('tanggal_sidang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');

    }
};
