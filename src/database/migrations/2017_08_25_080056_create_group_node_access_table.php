<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupNodeAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_group_node_access', function($table) 
        {
            $table->increments('id');
            $table->integer('group_id')->references('id')->on('rs_groups')->unsigned();
            $table->integer('node_id')->references('id')->on('rs_nodes')->unsigned();
            $table->tinyInteger('access')->default(0);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('rs_groups')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
        Schema::table('rs_group_node_access', function(Blueprint $table)
        {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['node_id']);
        });
        
        Schema::dropIfExists('rs_group_node_access');
    }
}
