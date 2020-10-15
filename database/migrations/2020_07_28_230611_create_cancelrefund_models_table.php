<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelrefundModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancelrefund_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type')->nullable();
            $table->string('refid')->nullable();
            $table->string('jobid')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->string('refundid')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('cancelrefund_models');
    }
}
