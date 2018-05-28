<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id')->nullable();
            $table->string('name')->nullable();
            $table->string('content')->nullable();
            $table->string('allias')->nullable();
            $table->integer('menuindex')->unsigned()->default(0);
            $table->integer('theme_id')->unsigned()->nullable()->default(1);
            $table->timestamps();

            //$table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
            //$table->foreign('theme_id')->references('id')->on('site_page_themes')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_pages');
    }
}
