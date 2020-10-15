<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('roles')->nullable();
            $table->bigInteger('userid');
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('avatar')->nullable();
            $table->string('coverimg')->nullable();
            $table->string('phone')->nullable();
            $table->string('countryISO')->nullable();
            $table->text('address')->nullable();
            $table->string('long')->nullable();
            $table->string('lat')->nullable();
            $table->text('language')->nullable();
            $table->text('exspec')->nullable();
            $table->text('license')->nullable();
            $table->tinyInteger('service')->nullable();
            $table->tinyInteger('hiretype')->nullable();
            $table->text('serviceactivity')->nullable();
            $table->tinyInteger('live_in')->nullable();
            $table->tinyInteger('paymethod')->nullable();
            $table->string('rate')->nullable();
            $table->tinyInteger('review')->nullable();
            $table->string('price')->nullable();
            $table->text('bio')->nullable();
            $table->string('account_id')->nullable();
            $table->string('candidateid')->nullable();
            $table->string('orderid')->nullable();
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
        Schema::dropIfExists('profile_models');
    }
}
