<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name')->comment("Translatable")->nullable();
            $table->string('email')->unique();
            $table->enum('account_type', ['personal', 'corporate', 'employee']);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('featured')->default(false);
            $table->boolean('active')->default(true);
            $table->boolean('hidden')->default(false);
            $table->string('username')->unique();
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->unsignedBigInteger('country_id')->nullable(); # foreign
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onDelete('cascade');
            $table->unsignedBigInteger('company_id')->nullable(); # foreign
            $table->foreign('company_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
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
