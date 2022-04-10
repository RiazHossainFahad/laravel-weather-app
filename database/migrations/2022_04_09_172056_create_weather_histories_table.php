<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_histories', function (Blueprint $table) {
            $table->id();
            $table->string('city')->index();
            $table->double('lon')->nullable();
            $table->double('lat')->nullable();
            $table->string('weather_condition')->nullable()->index();
            $table->double('temperature')->nullable();
            $table->double('temperature_feel_like')->nullable();
            $table->double('humidity')->nullable();
            $table->double('wind_speed')->nullable();
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
        Schema::dropIfExists('weather_histories');
    }
}