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
        Schema::create('opozorilas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade'); // Če uporabnik izbriše svoj račun, se opozorila izbrišejo
            $table->foreignId('group_id')
                ->nullable()
                ->constrained('groups') // Povezava s tabelo groups
                ->onDelete('cascade'); // Če se skupina izbriše, se opozorila izbrišejo
            $table->string('message');
            $table->boolean('prebrano')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opozorilas');
    }
};
