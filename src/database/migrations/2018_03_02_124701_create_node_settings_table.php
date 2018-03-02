<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_node_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('node_id')->references('id')->on('rs_nodes')->unsigned();
            $table->boolean('use_response_cache')->default(false);
            $table->string('node_icon')->nullable()->default(null);
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
        Schema::table('rs_node_settings', function(Blueprint $table)
        {
            $table->dropForeign(['node_id']);
        });

        Schema::dropIfExists('rs_node_settings');
    }
}
