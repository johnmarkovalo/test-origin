<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOccupants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('occupants', function (Blueprint $table) {
            $table->string('vaccination')->after('type')->nullable();
            $table->string('gender')->after('address')->nullable();
            $table->date('birthdate')->after('gender')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('occupants', function (Blueprint $table) {
            $table->dropColumn('vaccination');
            $table->dropColumn('gender');
            $table->dropColumn('birthdate');
        });
    }
}
