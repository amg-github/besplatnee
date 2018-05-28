<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadingAliasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('heading_aliases', function (Blueprint $table) {
            $table->integer('heading_id')->unsigned();
            $table->string('language')->nullable();
            $table->string('alias_local')->nullable();
            $table->string('alias_international')->nullable();
            $table->string('property_id')->nullable();
            $table->string('property_value')->nullable();
            $table->string('auto_words_before')->nullable();
            $table->string('auto_words_after')->nullable();
            $table->timestamps();

            $table->primary(['heading_id', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('heading_aliases');
    }
}
