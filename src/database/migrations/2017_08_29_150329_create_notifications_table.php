<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_notifications', function($table) 
        {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('rs_users')->unsigned();
            $table->integer('node_id')->references('id')->on('rs_nodes')->unsigned()->nullable()->default(null);
            $table->string('message');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('rs_users')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('node_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_notifications', function(Blueprint $table)
        {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['node_id']);
        });

        Schema::dropIfExists('rs_notifications');
    }
}
