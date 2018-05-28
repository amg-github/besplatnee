<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advert_bills', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('advert_template_id')->unsigned()->nullable();
            $table->timestamp('deleted_at');
            /*
                1 - создано
                2 - ожидается оплата
                3 - оплата прошла
                4 - отменено
                5 - закончился срок публикации
                0 - активно
            */
            $table->integer('status')->unsigned()->default(1); 
            $table->float('price', 12, 2)->default(0.00);

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
        Schema::dropIfExists('advert_bills');
    }
}
