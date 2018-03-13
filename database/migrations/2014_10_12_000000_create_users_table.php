<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('legal_name');
            $table->string('inn');
            $table->string('city');
            $table->string('street');
            $table->string('house');
            $table->string('phone', '20');
            $table->boolean('payment_type')->default(0);
            $table->boolean('access')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->string('api_token',60)->unique()->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
