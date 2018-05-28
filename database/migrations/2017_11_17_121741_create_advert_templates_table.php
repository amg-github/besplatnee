<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_templates', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->integer('border_width')->unsigned()->nullable();
            $table->string('border_color')->nullable();
            $table->string('background')->nullable();
            $table->integer('font_size')->unsigned()->nullable();
            $table->string('font_color')->nullable();
            $table->tinyInteger('bold')->unsigned()->default(0);

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
        Schema::dropIfExists('advert_templates');
    }
}
