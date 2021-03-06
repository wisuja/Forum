<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('channel_id');
            $table->unsignedInteger('visits')->default(0);
            $table->string('title');
            $table->text('body');
            $table->string('image_path')->nullable();
            // $table->unsignedBigInteger('best_reply_id')->nullable();
            $table->boolean('locked')->default(false);
            $table->timestamps();

            // $table->foreign('best_reply_id')
            //     ->references('id')
            //     ->on('replies')
            //     ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
