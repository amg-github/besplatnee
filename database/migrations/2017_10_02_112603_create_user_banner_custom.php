<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBannerCustom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_banner_custom', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('banner_id')->unsigned();
            $table->tinyInteger('hidden')->unsigned()->default(0);

            $table->primary(['user_id', 'banner_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_banner_custom');
    }
}
