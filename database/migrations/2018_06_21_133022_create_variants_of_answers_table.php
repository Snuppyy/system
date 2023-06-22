<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantsOfAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants_of_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_question');
            $table->text('name_ru')->nullable();
            $table->text('name_uz')->nullable();
            $table->text('name_en')->nullable();
            $table->integer('status')->default();
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
        Schema::dropIfExists('variants_of_answers');
    }
}
