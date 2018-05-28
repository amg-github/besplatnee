<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('advert_id')->unsigned()->nullable();
            $table->string('type')->default('*/*');
            $table->string('path')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
            
            $table->foreign('advert_id')->references('id')->on('adverts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_media');
    }
}
