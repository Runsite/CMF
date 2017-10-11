<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupModelAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_group_model_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->references('id')->on('rs_groups')->unsigned();
            $table->integer('model_id')->references('id')->on('rs_models')->unsigned();
            $table->tinyInteger('access')->default(2);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('rs_groups')->onUpdate('RESTRICT')->onDelete('CASCADE');
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
        Schema::table('rs_group_model_access', function(Blueprint $table)
        {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['model_id']);
        });
        
        Schema::dropIfExists('rs_group_model_access');
    }
}
