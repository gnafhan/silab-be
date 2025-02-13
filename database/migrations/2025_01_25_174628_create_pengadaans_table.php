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
        Schema::create('pengadaans', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->text('spesifikasi');
            $table->integer('jumlah');
            $table->bigInteger('harga_item');
            $table->date('bulan_pengadaan');
            $table->unsignedBigInteger('labolatory_id');
            $table->foreign('labolatory_id')->references('id')->on('labolatories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaans');
    }
};
