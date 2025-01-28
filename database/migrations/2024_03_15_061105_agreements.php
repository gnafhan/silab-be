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
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_student');
            $table->index('id_student');
            $table->foreign('id_student')->references('id')->on('students')->onUpdate('no action')->onDelete('cascade');
            $table->foreignId('id_project');
            $table->index('id_project');
            $table->foreign('id_project')->references('id')->on('projects')->onUpdate('no action')->onDelete('cascade');
            $table->enum('agreement_status', ["Revisi", "Proses", "Terplotting Bimbingan"]);
            $table->integer('progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agreements');
    }
};