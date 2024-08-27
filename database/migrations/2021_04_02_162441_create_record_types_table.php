<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('record_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment("Translatable");
            $table->integer('type_order');
            $table->boolean('is_system')->default(false)->comment("System Record Types Cannot Be Edited Or Deleted Even By Admins");
            $table->boolean('required')->default(false);
            $table->unsignedBigInteger('user_id')->nullable(); # foreign
            $table->enum('account_type', ['personal', 'corporate', 'employee'])->default('personal');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->unique(['user_id', 'name', 'account_type']);
            $table->unique(['user_id', 'type_order', 'account_type']);
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
        Schema::dropIfExists('record_types');
    }
}
