<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->dateTime('deadline')->nullable();
            $table->unsignedBigInteger('itemOrder');

            $table->unsignedBigInteger('colId');
            $table->unsignedBigInteger('assignedUserId')->nullable();
            $table->unsignedBigInteger('ownerUserId');

            $table->foreign('colId')->references('id')->on('cols');
            $table->foreign('assignedUserId')->references('id')->on('users');
            $table->foreign('ownerUserId')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
