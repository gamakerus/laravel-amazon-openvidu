<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterviewListModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interview_list_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('provider')->nullable();
            $table->bigInteger('client')->nullable();
            $table->tinyInteger('week')->nullable();
            $table->tinyInteger('start')->nullable();
            $table->tinyInteger('end')->nullable();
            $table->tinyInteger('settime')->nullable();
            $table->string('room')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('checkflag')->nullable();
            $table->tinyInteger('cchk')->nullable();
            $table->tinyInteger('pchk')->nullable();
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
        Schema::dropIfExists('interview_list_models');
    }
}
