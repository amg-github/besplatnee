<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_id')->unsigned()->default(0)->nullable();
            $table->integer('sortindex')->unsigned()->default(0);
            $table->tinyInteger('active')->unsigned()->default(1);
            $table->tinyInteger('show_in_top_menu')->unsigned()->default(0);
            $table->timestamps();

            //$table->foreign('parent_id')->references('id')->on('headings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('headings');
    }
}
