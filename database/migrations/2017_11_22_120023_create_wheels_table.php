<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWheelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->string('name');
            $table->string('image');
            $table->string('code');
            $table->decimal('twidth', 4, 1);
            $table->integer('tdiameter');
            $table->string('model', '50');
            $table->integer('hole_count');
            $table->string('pcd', 7);
            $table->string('et', 7);
            $table->decimal('dia', 4, 1);
            $table->string('tcae', 100)->nullable();
            $table->string('type', 20);
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
        Schema::dropIfExists('wheels');
    }
}
