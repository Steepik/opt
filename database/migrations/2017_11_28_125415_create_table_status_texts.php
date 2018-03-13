<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTableStatusTexts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_texts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->timestamps();
        });

        //status default records
        $data = array(
            ['text' => 'Ожидается проверка'],
            ['text' => 'Готов к  отгрузке | ожидается оплата'],
            ['text' => 'Отменён'],
            ['text' => 'Водитель в пути'],
            ['text' => '<b>Объединен</b><br/> готов к отгрузке | ожидается оплата'],
            ['text' => 'Завершён'],
            ['text' => 'Отменён модератором']
        );

        DB::table('status_texts')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_texts');
    }
}
