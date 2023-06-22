<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDropInCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drop_in_centers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('encoding')->nullable();
            $table->string('name_ru')->nullable();
            $table->string('name_uz')->nullable();
            $table->string('name_en')->nullable();
            $table->integer('region')->nullable();
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
        Schema::dropIfExists('drop_in_centers');
    }
}
