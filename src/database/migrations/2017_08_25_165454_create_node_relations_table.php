<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_node_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('language_id')->references('id')->on('rs_languages')->unsigned();
            $table->integer('node_id')->references('id')->on('rs_nodes')->unsigned();
            $table->integer('field_id')->references('id')->on('rs_fields')->unsigned();
            $table->integer('related_node_id')->references('id')->on('rs_nodes')->unsigned();
            $table->timestamps();

            $table->foreign('language_id')->references('id')->on('rs_languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('node_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('field_id')->references('id')->on('rs_fields')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('related_node_id')->references('id')->on('rs_nodes')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_node_relations', function(Blueprint $table)
        {
            $table->dropForeign(['language_id']);
            $table->dropForeign(['node_id']);
            $table->dropForeign(['field_id']);
            $table->dropForeign(['related_node_id']);
        });
        
        Schema::dropIfExists('rs_node_relations');
    }
}
