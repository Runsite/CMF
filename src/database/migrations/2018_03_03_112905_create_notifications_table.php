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
        Schema::create('rs_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->references('id')->on('rs_nodes')->unsigned()->nullable()->default(null);
            $table->integer('user_id')->references('id')->on('rs_users')->unsigned();
            $table->boolean('is_reviewed')->default(false);
            $table->boolean('is_sounded')->default(false);
            $table->string('message');
            $table->string('icon_name');
            $table->timestamps();

            $table->foreign('node_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('rs_users')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_node_settings', function(Blueprint $table)
        {
            $table->dropForeign(['node_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('rs_notifications');
    }
}
