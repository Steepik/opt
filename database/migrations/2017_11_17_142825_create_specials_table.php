<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->string('name');
            $table->string('image');
            $table->string('code');
            $table->decimal('twidth', 3, 1);
            $table->integer('tprofile');
            $table->integer('tdiameter');
            $table->string('load_index', '25');
            $table->string('speed_index', '25');
            $table->string('tseason', '25');
            $table->string('model', '50');
            $table->string('tcae', '100')->nullable();
            $table->string('model_class', '3');
            $table->integer('price_opt');
            $table->integer('price_roz');
            $table->integer('quantity');
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
        Schema::dropIfExists('specials');
    }
}
