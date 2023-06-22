<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExportTempExcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_temp_excels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');
            $table->text('user');
            $table->longText('comment');
            $table->date('date');
            $table->time('start');
            $table->time('end');
            $table->time('result');
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
        Schema::dropIfExists('export_temp_excels');
    }
}
