<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_search_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('rs_users')->unsigned();
            $table->string('search_key', 32);
            $table->timestamps();

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
        Schema::table('rs_search_history', function(Blueprint $table)
        {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('rs_search_history');
    }
}
