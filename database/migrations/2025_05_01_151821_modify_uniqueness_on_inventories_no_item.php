<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, check if there's a unique index on no_item column
        $indexes = DB::select("SHOW INDEXES FROM inventories WHERE Column_name = 'no_item' AND Non_unique = 0");
        
        if (!empty($indexes)) {
            // Drop existing unique index if it exists
            Schema::table('inventories', function (Blueprint $table) {
                $table->dropUnique(['no_item']);
            });
        }
        
        // Add a new unique index that includes deleted_at
        Schema::table('inventories', function (Blueprint $table) {
            $table->unique(['no_item', 'deleted_at'], 'inventories_no_item_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the combined unique index
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropUnique('inventories_no_item_unique');
        });
        
        // Restore the original unique index on no_item
        Schema::table('inventories', function (Blueprint $table) {
            $table->unique(['no_item']);
        });
    }
};
