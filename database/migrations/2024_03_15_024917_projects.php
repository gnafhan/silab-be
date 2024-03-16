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
            $table->foreign(['id_lecturer'])->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['id_period'])->references(['id'])->on('periods')->onUpdate('no action')->onDelete('cascade');
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