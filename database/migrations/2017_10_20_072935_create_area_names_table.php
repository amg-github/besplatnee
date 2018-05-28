<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_names', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->nullable();
            $table->string('language')->nullable()->default('en');

            $table->string('nominative_local')->nullable(); // именительный
            $table->string('genitive_local')->nullable(); // родительный
            $table->string('accusative_local')->nullable(); // винительный
            $table->string('dative_local')->nullable(); // дательный
            $table->string('ergative_local')->nullable(); // предложный

            $table->string('nominative_international')->nullable(); // именительный латинскими символами
            $table->string('genitive_international')->nullable(); // родительный латинскими символами
            $table->string('accusative_international')->nullable(); // винительный латинскими символами
            $table->string('dative_international')->nullable(); // дательный латинскими символами
            $table->string('ergative_international')->nullable(); // предложный латинскими символами

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
        Schema::dropIfExists('area_names');
    }
}
