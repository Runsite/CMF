<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_node_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id')->references('id')->on('rs_models')->unsigned();
            $table->integer('parent_node_id')->references('id')->on('rs_nodes')->unsigned()->nullable();
            $table->tinyInteger('type');
            $table->timestamps();

            $table->foreign('model_id')->references('id')->on('rs_models')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('parent_node_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_node_analytics', function(Blueprint $table)
        {
            $table->dropForeign(['model_id']);
            $table->dropForeign(['parent_node_id']);
        });
        
        Schema::dropIfExists('rs_node_analytics');
    }
}
