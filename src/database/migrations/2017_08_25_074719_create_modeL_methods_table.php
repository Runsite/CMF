<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_model_methods', function($table) 
        {
            $table->increments('id');
            $table->integer('model_id')->references('id')->on('rs_models')->unsigned();
            $table->string('get')->nullable()->default(null);
            $table->string('post')->nullable()->default(null);
            $table->string('patch')->nullable()->default(null);
            $table->string('delete')->nullable()->default(null);
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
        Schema::table('rs_model_methods', function(Blueprint $table)
        {
            $table->dropForeign(['model_id']);
        });

        Schema::dropIfExists('rs_model_methods');
    }
}
