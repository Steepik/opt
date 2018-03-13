<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderMerges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_merges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->unsigned();
            $table->integer('oid')->unsigned();
            $table->integer('mid')->unsigned();
            $table->integer('cnum');
            $table->string('tcae', 50)->nullable();
            $table->foreign('oid')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('uid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mid')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('order_merges');
    }
}
