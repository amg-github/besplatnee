<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->unsigned()->nullable();
            $table->integer('creator_id')->unsigned()->nullable();
            $table->integer('heading_id')->unsigned()->nullable();
            $table->integer('context_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('main_phrase')->nullable();
            $table->float('price', 12, 2)->default(0.00);
            $table->tinyInteger('active')->unsigned()->default(0);
            $table->tinyInteger('blocked')->unsigned()->default(0);
            $table->tinyInteger('show_phone')->unsigned()->default(1);
            $table->text('content')->nullable();
            $table->text('extend_content')->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('send_to_print')->unsigned()->default(0);
            $table->string('site_url')->nullable();
            /*
                0 - новый
                1 - требует оплаты
                2 - оплачено
                3 - в архиве
            */
            $table->integer('status')->unsigned()->default(1);
            $table->tinyInteger('approved')->unsigned()->default(0);
            $table->tinyInteger('dubplicate_in_all_cities')->unsigned()->default(0);
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->timestamps();
            $table->timestamp('fakeupdated_at')->nullable();
            $table->softDeletes();
            $table->integer('viewcount')->unsigned()->nullable()->default(0);
            $table->integer('clickcount')->unsigned()->nullable()->default(0);
            $table->timestamp('unpublished_on')->nullable();

            $table->foreign('owner_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('SET NULL');
            //$table->foreign('heading_id')->references('id')->on('headings')->onDelete('cascade');
            //$table->foreign('context_id')->references('id')->on('contexts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adverts');
    }
}
