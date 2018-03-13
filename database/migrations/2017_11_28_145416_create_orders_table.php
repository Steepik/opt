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
            $table->integer('uid')->unsigned();
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
            $table->integer('cnum');
            $table->string('tcae', 50)->nullable();
            $table->integer('ptype')->nullable();
            $table->integer('count');
            $table->integer('sid')->unsigned();
            $table->foreign('sid')->references('id')->on('status_texts')->onDelete('cascade');
            $table->boolean('merged');
            $table->boolean('archived');
            $table->boolean('commented')->default(0);
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
