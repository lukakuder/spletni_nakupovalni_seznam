<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReminderDateToShoppingListsTable extends Migration
{
    public function up()
    {
        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->date('reminder_date')->nullable()->after('updated_at');
        });
    }

    public function down()
    {
        Schema::table('shopping_lists', function (Blueprint $table) {
            $table->dropColumn('reminder_date');
        });
    }
}
