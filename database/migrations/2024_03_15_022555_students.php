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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user');
            $table->index('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->string('NIM');
            $table->string('semester');
            $table->integer('IPK');
            $table->integer('SKS');
            $table->string('phone_number');
            $table->string('skill');
            $table->string('experience');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
