<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_paths', function($table) 
        {
            $table->increments('id');
            $table->integer('node_id')->references('id')->on('rs_nodes')->unsigned();
            $table->integer('language_id')->references('id')->on('rs_languages')->unsigned();
            $table->string('name', 1024);
            $table->timestamps();

            $table->foreign('node_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('language_id')->references('id')->on('rs_languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_paths', function(Blueprint $table)
        {
            $table->dropForeign(['node_id']);
            $table->dropForeign(['language_id']);
        });

        Schema::dropIfExists('rs_paths');
    }
}
