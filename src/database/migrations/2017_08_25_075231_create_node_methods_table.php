<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_node_methods', function($table) 
        {
            $table->increments('id');
            $table->integer('node_id')->references('id')->on('rs_nodes')->unsigned();
            $table->string('get')->nullable()->default(null);
            $table->string('post')->nullable()->default(null);
            $table->string('patch')->nullable()->default(null);
            $table->string('delete')->nullable()->default(null);
            $table->timestamps();

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
        Schema::table('rs_node_methods', function(Blueprint $table)
        {
            $table->dropForeign(['node_id']);
        });

        Schema::dropIfExists('rs_node_methods');
    }
}
