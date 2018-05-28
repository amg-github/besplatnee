<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAdvertCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_advert_customs', function (Blueprint $table) {
            $table->integer('advert_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->tinyInteger('hidden')->unsigned()->default(0);
            $table->tinyInteger('favorite')->unsigned()->default(0);
            $table->integer('sort')->unsigned()->default(0);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->primary(['advert_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_advert_customs');
    }
}
