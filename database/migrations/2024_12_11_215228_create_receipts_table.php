<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ta metoda ustvari tabelo 'receipts', ki je namenjena shranjevanju podatkov o naloženih računih.
     * Povezana je s tabelo 'shopping_lists' prek zunanjega ključa (shopping_list_id).
     */
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id(); // Primarni ključ
            $table->foreignId('shopping_list_id') // Zunanji ključ za tabelo shopping_lists
            ->constrained('shopping_lists')
                ->onDelete('cascade'); // Samodejno brisanje povezanih računov ob izbrisu nakupovalnega seznama
            $table->string('name'); // Ime računa
            $table->string('file_path'); // Pot do shranjene datoteke
            $table->timestamps(); // Časovni žigi za ustvarjanje in posodobitev
        });
    }

    /**
     * Izbriše tabelo 'receipts', če obstaja.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
