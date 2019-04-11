<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('office_id')->unsigned();
            $table->integer('year');
            $table->integer('m1');
            $table->integer('m2');
            $table->integer('m3');
            $table->integer('m4');
            $table->integer('m5');
            $table->integer('m6');
            $table->integer('m7');
            $table->integer('m8');
            $table->integer('m9');
            $table->integer('m10');
            $table->integer('m11');
            $table->integer('m12');
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
        Schema::dropIfExists('calendars');
    }
}
