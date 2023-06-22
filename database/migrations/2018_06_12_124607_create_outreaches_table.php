<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutreachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outreaches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('region');
            $table->char('encoding', '7')->nullable();
            $table->string('f_name');
            $table->string('s_name');
            $table->date('birthday')->nullable();
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
        Schema::dropIfExists('outreaches');
    }
}
