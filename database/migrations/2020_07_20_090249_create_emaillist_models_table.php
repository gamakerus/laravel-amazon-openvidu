<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmaillistModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emaillist_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('user')->nullable();
            $table->bigInteger('specific')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('filename')->nullable();
            $table->string('emailid')->nullable();
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
        Schema::dropIfExists('emaillist_models');
    }
}
