<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_model_settings', function($table) 
        {
            $table->increments('id');
            $table->integer('model_id')->references('id')->on('rs_models')->unsigned();
            $table->boolean('show_in_admin_tree')->default(true);
            $table->boolean('use_response_cache')->default(false);
            $table->boolean('slug_autogeneration')->default(false);
            $table->string('nodes_ordering');
            $table->string('dynamic_model')->nullable()->default(null);
            $table->timestamps();

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
        Schema::table('rs_model_settings', function(Blueprint $table)
        {
            $table->dropForeign(['model_id']);
        });

        Schema::dropIfExists('rs_model_settings');
    }
}
