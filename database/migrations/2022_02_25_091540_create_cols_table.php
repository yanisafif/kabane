<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cols', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 15);
            $table->string('colorHexa', 6);
            $table->timestamps();
            $table->unsignedBigInteger('colOrder');

            $table->unsignedBigInteger('kanbanId');
            $table->foreign('kanbanId')->references('id')->on('kanbans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cols');
    }
}
