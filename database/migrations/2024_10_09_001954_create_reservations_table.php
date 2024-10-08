<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->foreignId('event_id')->constrained()->onUpdate('cascade');
            $table->integer('number_of_people');
            $table->datetime('canceled_date')->nullable();
            $table->timestamps();           
            // 外部キーと同期をとりながら更新
            // foreignIdでメソッド名を付与、constrainedで外部キー制約、
            // onUpdate('cascade')で更新したときに同期
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
