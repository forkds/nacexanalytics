<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConcepts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('concepts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('office_id')->unsigned();
            $table->string('code');
            $table->string('name');
            $table->integer('year');
            $table->decimal('m1', 10, 2);
            $table->decimal('m2', 10, 2);
            $table->decimal('m3', 10, 2);
            $table->decimal('m4', 10, 2);
            $table->decimal('m5', 10, 2);
            $table->decimal('m6', 10, 2);
            $table->decimal('m7', 10, 2);
            $table->decimal('m8', 10, 2);
            $table->decimal('m9', 10, 2);
            $table->decimal('m10', 10, 2);
            $table->decimal('m11', 10, 2);
            $table->decimal('m12', 10, 2);
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
        //
        Schema::dropIfExists('concepts');

    }
}
