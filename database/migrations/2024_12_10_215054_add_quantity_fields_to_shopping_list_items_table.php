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
        Schema::table('list_items', function (Blueprint $table) {
            $table->integer('total_quantity')->default(1); // Skupno število potrebnih izdelkov
            $table->integer('purchased_quantity')->default(0); // Število kupljenih izdelkov
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_items', function (Blueprint $table) {
            $table->dropColumn(['total_quantity', 'purchased_quantity']);
        });
    }
};
