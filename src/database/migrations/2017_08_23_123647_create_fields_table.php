<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_fields', function($table) 
        {
            $table->increments('id');
            $table->integer('model_id')->references('id')->on('rs_models')->unsigned();
            $table->integer('type_id');
            $table->integer('group_id')->references('id')->on('rs_field_groups')->unsigned()->nullable()->default(null);
            $table->integer('position');
            $table->string('name');
            $table->string('display_name');
            $table->string('hint')->nullable()->default(null);
            $table->boolean('is_common')->default(false);
            $table->boolean('is_visible_in_nodes_list')->default(true);
            $table->timestamps();

            $table->foreign('model_id')->references('id')->on('rs_models')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('group_id')->references('id')->on('rs_field_groups')->onUpdate('RESTRICT')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_fields', function(Blueprint $table)
        {
            $table->dropForeign(['model_id']);
        });
        
        Schema::dropIfExists('rs_fields');
    }
}
