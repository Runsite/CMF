<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_field_settings', function($table) 
        {
            $table->increments('id');
            $table->integer('field_id')->references('id')->on('rs_fields')->unsigned();
            $table->string('parameter');
            $table->string('value')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('field_id')->references('id')->on('rs_fields')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_field_settings', function(Blueprint $table)
        {
            $table->dropForeign(['field_id']);
        });

        Schema::dropIfExists('rs_field_settings');
    }
}
