<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertContext extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_context', function (Blueprint $table) {
            $table->integer('advert_id')->unsigned();
            $table->integer('context_id')->unsigned();

            $table->primary(['advert_id', 'context_id']);

            $table->foreign('advert_id')->references('id')->on('adverts')->onDelete('cascade');
            $table->foreign('context_id')->references('id')->on('contexts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advert_context');
    }
}
