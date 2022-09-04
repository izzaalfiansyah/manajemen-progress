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
        Schema::create('user_tugas', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->bigInteger('userId')->unsigned();
            $table->timestamp('deadline');
            $table->enum('status', ['1', '0'])->default('0')->comment('1: selesai, 0: belum');
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
        Schema::dropIfExists('user_tugas');
    }
};
