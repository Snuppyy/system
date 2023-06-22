<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsibilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');
            $table->json('position')->nullable();
            $table->integer('type')->nullable();
            $table->text('name_ru');
            $table->text('name_uz')->nullable();
            $table->text('name_en')->nullable();
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
        Schema::dropIfExists('responsibilities');
    }
}
