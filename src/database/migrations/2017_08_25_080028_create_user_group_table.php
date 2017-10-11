<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_user_group', function($table) 
        {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('rs_users')->unsigned();
            $table->integer('group_id')->references('id')->on('rs_groups')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('rs_users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('group_id')->references('id')->on('rs_groups')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_user_group', function(Blueprint $table)
        {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['group_id']);
        });
        
        Schema::dropIfExists('rs_user_group');
        Schema::dropIfExists('password_resets');
    }
}
