<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id')->unsigned();
            $table->integer('region_id')->unsigned();
            $table->string('name_key')->nullable();
            $table->tinyInteger('active')->unsigned()->default(1);
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->decimal('northeast_latitude', 10, 6)->nullable();
            $table->decimal('northeast_longitude', 10, 6)->nullable();
            $table->decimal('southwest_latitude', 10, 6)->nullable();
            $table->decimal('southwest_longitude', 10, 6)->nullable();
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
        Schema::dropIfExists('cities');
    }
}
