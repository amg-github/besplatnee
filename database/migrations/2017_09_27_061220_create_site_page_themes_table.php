<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitePageThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_page_themes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('font_size')->unsigned()->default(14);
            $table->string('font_family')->default('Arial');
            $table->string('font_color')->default('black');
            $table->string('background_color')->default('white');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_page_themes');
    }
}
