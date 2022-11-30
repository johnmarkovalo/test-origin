<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsolationRoomRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isolation_room_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('isolation_room_id');
            $table->foreign('isolation_room_id')->references('id')->on('isolation_rooms')->onDelete('cascade');
            $table->unsignedBigInteger('occupant_id');
            $table->foreign('occupant_id')->references('id')->on('occupants')->onDelete('cascade');
            $table->string('type');
            $table->string('status');
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
        Schema::dropIfExists('isolation_room_requests');
    }
}
