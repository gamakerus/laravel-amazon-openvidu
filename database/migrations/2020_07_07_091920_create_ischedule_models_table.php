<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIscheduleModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ischedule_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('userid')->nullable();
            $table->tinyInteger('week')->nullable();
            $table->tinyInteger('checked')->nullable();
            $table->tinyInteger('start')->nullable();
            $table->tinyInteger('end')->nullable();
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
        Schema::dropIfExists('ischedule_models');
    }
}
