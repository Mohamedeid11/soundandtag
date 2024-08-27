<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTryingRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trying_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('trying_users')
                  ->onDelete('cascade');
            $table->string('first_name')->comment('Translatable');
            $table->string('first_name_meaning')->nullable()->comment('Translatable');
            $table->string('first_name_file');
            $table->string('last_name')->comment('Translatable');
            $table->string('last_name_meaning')->nullable()->comment('Translatable');
            $table->string('last_name_file');
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
        Schema::dropIfExists('trying_records');
    }
}
