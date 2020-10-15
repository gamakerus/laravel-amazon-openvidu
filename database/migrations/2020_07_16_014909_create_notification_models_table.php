<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('user')->nullable();
            $table->bigInteger('specific')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('filename')->nullable();
            $table->string('notid')->nullable();
            $table->text('checked')->nullable();
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
        Schema::dropIfExists('notification_models');
    }
}
