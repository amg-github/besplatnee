<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contexts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('allias', 100)->unique();
            $table->integer('type')->unsigned()->default(1);
            $table->string('description')->nullable();
            $table->tinyInteger('active')->unsigned()->default(1);
            $table->tinyInteger('blocked')->unsigned()->default(0);
            $table->string('address')->nullable();
            $table->string('logo')->nullable();
            $table->integer('status')->unsigned()->default(1);
            $table->integer('theme_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
            //$table->foreign('theme_id')->references('id')->on('context_themes')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contexts');
    }
}
