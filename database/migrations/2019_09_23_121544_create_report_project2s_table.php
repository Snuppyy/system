<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportProject2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_project2s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');
            $table->date('date');
            $table->integer('region');
            $table->integer('miovisitions')->nullable();
            $table->integer('webinar')->nullable();
            $table->integer('seminar')->nullable();
            $table->integer('meetings')->nullable();
            $table->integer('report_month')->nullable();
            $table->integer('report')->nullable();
            $table->integer('editor');
            $table->dateTime('complete')->nullable();
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
        Schema::dropIfExists('report_project2s');
    }
}
