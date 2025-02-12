<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')
                  ->constrained('inventories')
                  ->onDelete('cascade');
            $table->string('filepath');
            $table->string('filename')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_galleries');
    }
};