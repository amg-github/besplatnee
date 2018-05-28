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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            /* main fields */
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');
            /* state */
            $table->tinyInteger('active')->unsigned()->default(1);
            $table->tinyInteger('blocked')->unsigned()->default(0);
            /* profile */
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('patronymic')->nullable();
            // photo url for public path
            $table->string('photo')->nullable();
            // 0 - undefined
            // 1 - male
            // 2 - female
            $table->tinyInteger('gender')->unsigned()->default(0);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
