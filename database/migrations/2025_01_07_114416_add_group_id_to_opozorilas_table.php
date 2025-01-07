<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('opozorilas', function (Blueprint $table) {
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('opozorilas', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
        });
    }


};
