<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelDependenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_model_dependencies', function($table) 
        {
            $table->increments('id');
            $table->integer('model_id')->references('id')->on('rs_models')->unsigned();
            $table->integer('depended_model_id')->references('id')->on('rs_models')->unsigned();
            $table->integer('position');
            $table->timestamps();

            $table->foreign('model_id')->references('id')->on('rs_models')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
        Schema::table('rs_model_dependencies', function(Blueprint $table)
        {
            $table->dropForeign(['model_id']);
            $table->dropForeign(['depended_model_id']);
        });
        
        Schema::dropIfExists('rs_model_dependencies');
    }
}
