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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lecturer');
            $table->index('id_lecturer');
            $table->foreign('id_lecturer')->references('id')->on('lecturers')->onUpdate('no action')->onDelete('cascade');
            $table->foreignId('id_period');
            $table->index('id_period');
            $table->foreign('id_period')->references('id')->on('periods')->onUpdate('no action')->onDelete('cascade');
            $table->string('tittle');
            $table->string('agency');
            $table->string('description');
            $table->string('tools');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
