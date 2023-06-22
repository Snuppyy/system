<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_id');
            $table->integer('message_id');
            $table->integer('update_id')->nullable();
            $table->integer('author');
            $table->integer('user');
            $table->integer('reply_to_message_id')->nullable();
            $table->longText('text');
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
        Schema::dropIfExists('support_messages');
    }
}
