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
        Schema::create('pendadaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_student');
            $table->index('id_student');
            $table->foreign('id_student')->references('id')->on('students')->onUpdate('no action')->onDelete('cascade');
            $table->foreignId('id_lecturer');
            $table->index('id_lecturer');
            $table->foreign('id_lecturer')->references('id')->on('lecturers')->onUpdate('no action')->onDelete('cascade');
            $table->date('tanggal_sidang');
            $table->time('jam');
            $table->string('ruang');
            $table->integer('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendadaran');
    }
};