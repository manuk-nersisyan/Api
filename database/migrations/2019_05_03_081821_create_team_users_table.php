<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned();
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->string('role');
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
        Schema::dropIfExists('team_users');
    }
}
