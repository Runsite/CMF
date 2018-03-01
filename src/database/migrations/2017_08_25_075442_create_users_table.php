<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rs_users', function($table) 
        {
            $table->increments('id');
            $table->boolean('is_locked')->default(false);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable()->default(null);
            $table->rememberToken();
            $table->dateTime('last_action_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rs_users');
    }
}
