<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_property', function (Blueprint $table) {
            $table->integer('advert_id')->unsigned();
            $table->integer('property_id')->unsigned();
            $table->string('value')->nullable();

            $table->primary(['advert_id', 'property_id']);

            $table->foreign('advert_id')->references('id')->on('adverts')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_property');
    }
}
