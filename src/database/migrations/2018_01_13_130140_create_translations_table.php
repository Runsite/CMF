<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('language_id')->references('id')->on('rs_languages')->unsigned();
            $table->string('key');
            $table->string('value');
            $table->timestamps();

            $table->foreign('language_id')->references('id')->on('rs_languages')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_translations', function(Blueprint $table)
        {
            $table->dropForeign(['language_id']);
        });

        Schema::dropIfExists('rs_translations');
    }
}
