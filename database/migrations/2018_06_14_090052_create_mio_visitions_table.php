<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMioVisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mio_visitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');
            $table->integer('region');
            $table->dateTime('datetime');
            $table->char('phone', '19')->nullable();
            $table->integer('user');
            $table->string('name');
            $table->integer('type');
            $table->string('address');
            $table->char('coordinates', '20');
            $table->string('comments');
            $table->integer('availabilitySyringes2')->nullable();
            $table->integer('procurementSyringes2')->nullable();
            $table->integer('availabilitySyringes5')->nullable();
            $table->integer('procurementSyringes5')->nullable();
            $table->integer('availabilitySyringes10')->nullable();
            $table->integer('procurementSyringes10')->nullable();
            $table->integer('availabilityDoily')->nullable();
            $table->integer('procurementDoily')->nullable();
            $table->integer('availabilityCondomsM')->nullable();
            $table->integer('procurementCondomsM')->nullable();
            $table->integer('availabilityCondomsW')->nullable();
            $table->integer('procurementCondomsW')->nullable();
            $table->integer('availabilityHivBlood')->nullable();
            $table->integer('procurementHivBlood')->nullable();
            $table->integer('availabilityHivSpittle')->nullable();
            $table->integer('procurementHivSpittle')->nullable();
            $table->string('files')->nullable();
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
        Schema::dropIfExists('mio_visitions');
    }
}
