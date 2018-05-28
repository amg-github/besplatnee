<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned()->nullable();
            $table->integer('creator_id')->unsigned()->nullable();
            $table->string('type')->default('site');
            $table->string('allias');
            $table->string('description')->nullable();
            $table->string('site_url')->nullable();
            $table->string('logo')->nullable();
            $table->integer('status')->unsigned()->default(1);
            $table->integer('main_page')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('SET NULL');
            //$table->foreign('main_page')->references('id')->on('site_pages')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
