<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadingProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heading_property', function (Blueprint $table) {
            $table->integer('heading_id')->unsigned();
            $table->integer('property_id')->unsigned();
            $table->integer('sort')->unsigned()->default(0);

            $table->primary(['heading_id', 'property_id']);

            $table->foreign('heading_id')->references('id')->on('headings')->onDelete('cascade');
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
        Schema::dropIfExists('heading_property');
    }
}
