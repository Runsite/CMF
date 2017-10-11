<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_nodes', function($table) 
        {
            $table->increments('id');
            $table->integer('parent_id')->references('id')->on('rs_nodes')->unsigned()->nullable()->default(null);
            $table->integer('model_id')->references('id')->on('rs_models')->unsigned();
            $table->integer('position');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('model_id')->references('id')->on('rs_models')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_nodes', function(Blueprint $table)
        {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['model_id']);
        });

        Schema::dropIfExists('rs_nodes');
    }
}
