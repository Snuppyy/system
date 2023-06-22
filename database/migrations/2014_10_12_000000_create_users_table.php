<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_ru')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_uz')->nullable();
            $table->string('email')->unique();
            $table->string('avatar')->unique()->nullable();
            $table->string('password');
            $table->integer('role')->nullable();
            $table->integer('position')->nullable();
            $table->text('about')->nullable();
            $table->integer('status')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
