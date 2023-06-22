<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');
            $table->integer('project');
            $table->text('mark');
            $table->json('administrants');
            $table->json('helpers')->nullable();
            $table->json('supervisors')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->longText('text');
            $table->json('files')->nullable();
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
        Schema::dropIfExists('assignments');
    }
}
