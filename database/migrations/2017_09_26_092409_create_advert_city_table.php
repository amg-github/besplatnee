<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_city', function (Blueprint $table) {
            $table->integer('advert_id')->unsigned();
            $table->integer('city_id')->unsigned();

            $table->primary(['advert_id', 'city_id']);

            $table->foreign('advert_id')->references('id')->on('adverts')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_city');
    }
}
