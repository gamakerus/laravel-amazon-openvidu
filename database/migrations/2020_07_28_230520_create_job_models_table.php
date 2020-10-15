<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('jobid')->nullable();
            $table->bigInteger('provider')->nullable();
            $table->bigInteger('client')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->text('excludeday')->nullable();
            $table->tinyInteger('starttime')->nullable();
            $table->tinyInteger('endtime')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->tinyInteger('cancelflag')->nullable();
            $table->tinyInteger('reviewflag')->nullable();
            $table->text('canceltext')->nullable();
            $table->tinyInteger('service')->nullable();
            $table->text('serviceactivity')->nullable();
            $table->text('exspec')->nullable();
            $table->text('license')->nullable();
            $table->tinyInteger('paid')->nullable();
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
        Schema::dropIfExists('job_models');
    }
}
