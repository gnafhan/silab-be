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
        Schema::create('laboratorium_support', function (Blueprint $table) {
            $table->id(); //primary key
            $table->unsignedBigInteger('room_id'); //foreign key untuk rooms
            $table->string('support_type_1'); //tipe support
            $table->string('support_type_2')->nullable(); //tipe support
            $table->string('support_type_3')->nullable(); //tipe support
            $table->string('support_type_4')->nullable(); //tipe support
            $table->text('description')->nullable(); //deskripsi support
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratorium_support');
    }
};
