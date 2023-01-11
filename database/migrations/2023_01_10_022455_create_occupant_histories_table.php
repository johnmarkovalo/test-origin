<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOccupantHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('occupant_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('occupant_id');
            $table->foreign('occupant_id')->references('id')->on('occupants');
            $table->string('details');
            $table->string('activity_by');
            $table->date('date');
            $table->time('time');
            $table->string('remarks');
            $table->string('additional_remarks');
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
        Schema::dropIfExists('occupant_histories');
    }
}
