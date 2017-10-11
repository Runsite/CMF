<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupApplicationAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_group_application_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->references('id')->on('rs_groups')->unsigned();
            $table->integer('application_id')->references('id')->on('rs_applications')->unsigned();
            $table->tinyInteger('access')->default(0);
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('rs_groups')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('application_id')->references('id')->on('rs_applications')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_group_application_access', function(Blueprint $table)
        {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['application_id']);
        });

        Schema::dropIfExists('rs_group_application_access');
    }
}
