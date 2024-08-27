<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_hits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); # foreign
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->date('day');
            $table->integer('count');
            $table->timestamps();

            $table->unique(['user_id', 'day']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_hits');
    }
}
