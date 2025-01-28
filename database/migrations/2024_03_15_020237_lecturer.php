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
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user');
            $table->index('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->string('image_profile');
            $table->string('full_name');
            $table->string('front_title');
            $table->string('back_title');
            $table->string('NID');
            $table->string('phone_number');
            $table->integer('max_quota');
            $table->boolean('isKaprodi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturers');
     }
};
