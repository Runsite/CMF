<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeDependenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_node_dependencies', function($table) 
        {
            $table->increments('id');
            $table->integer('node_id')->references('id')->on('rs_nodes')->unsigned();
            $table->integer('depended_model_id')->references('id')->on('rs_models')->unsigned();
            $table->integer('position');
            $table->timestamps();

            $table->foreign('node_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('depended_model_id')->references('id')->on('rs_models')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_node_dependencies', function(Blueprint $table)
        {
            $table->dropForeign(['node_id']);
            $table->dropForeign(['depended_model_id']);
        });
        
        Schema::dropIfExists('rs_node_dependencies');
    }
}
