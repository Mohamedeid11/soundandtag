<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToTryingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trying_users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->comment('invited_by')->after('image'); # foreign
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trying_users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
}
