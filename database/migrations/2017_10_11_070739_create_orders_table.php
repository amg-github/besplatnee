<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('advert_id')->unsigned();
            $table->integer('creator_id')->unsigned();
            $table->integer('payer_id')->unsigned();
            /*
                0 - выставлен
                1 - оплачен
                2 - отменен
            */
            $table->integer('status')->unsigned();
            $table->timestamp('paided_at')->nullable();
            $table->string('paid_method')->nullable()->default('cash');
            $table->text('comment');
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
        Schema::dropIfExists('orders');
    }
}
