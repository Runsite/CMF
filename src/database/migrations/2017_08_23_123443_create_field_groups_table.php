<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_field_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('model_id')->references('id')->on('rs_models')->unsigned();
            $table->string('name');
            $table->integer('position');
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
        Schema::table('rs_field_groups', function(Blueprint $table)
        {
            $table->dropForeign(['model_id']);
        });

        Schema::dropIfExists('rs_field_groups');
    }
}
