<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_proyek')->index('contents_id_proyek');
            $table->string('thumbnail_image_url');
            $table->string('content_url')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_tittle')->nullable();
            $table->string('github_url')->nullable();
            $table->enum('tipe_konten', ['jurnal', 'tugas akhir']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
};
