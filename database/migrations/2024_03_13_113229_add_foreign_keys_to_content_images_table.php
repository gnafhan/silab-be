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
        Schema::table('content_images', function (Blueprint $table) {
            $table->foreign(['id_content'])->references(['id'])->on('contents')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_images', function (Blueprint $table) {
            $table->dropForeign('content_images_id_content_foreign');
        });
    }
};
