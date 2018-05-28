<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('creator_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('hover_text')->nullable();
            $table->string('contact_information')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->string('position')->default('header');
            $table->integer('sortindex')->unsigned()->default(0);
            $table->integer('heading_id')->unsigned()->nullable()->default(0);
            $table->tinyInteger('active')->unsigned()->default(1);
            $table->integer('width')->unsigned()->default(450);
            $table->integer('height')->unsigned()->default(118);
            $table->string('comment')->nullable();
            $table->tinyInteger('dublicate_in_all_cities')->unsigned()->default(0);
            $table->integer('organization_id')->unsigned()->nullable();
            $table->integer('status')->unsigned()->default(1);
            $table->integer('viewcount')->unsigned()->default(0);
            $table->integer('clickcount')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('SET NULL');
            //$table->foreign('heading_id')->references('id')->on('headings')->onDelete('SET NULL');
            //$table->foreign('organization_id')->references('id')->on('organizations')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
