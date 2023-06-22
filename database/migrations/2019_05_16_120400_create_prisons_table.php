<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrisonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prisons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');
            $table->integer('project');
            $table->text('encoding');
            $table->text('name_ru');
            $table->text('name_en')->nullable();
            $table->text('name_uz')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('prisons');
    }
}
