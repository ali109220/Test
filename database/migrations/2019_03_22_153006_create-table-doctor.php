<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDoctor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('arabic_name')->nullable();
            $table->enum('title', ['professor', 'consultant', 'specialist']);
            $table->enum('gender', ['male', 'female']);
            $table->unsignedInteger('specialty_id');
            $table->foreign('specialty_id')->references('id')->on('specialties');
            $table->unsignedInteger('sub_specialty_id')->nullable();
            $table->foreign('sub_specialty_id')->references('id')->on('subSpecialties');
            $table->unsignedInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->unsignedInteger('area_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->string('mobile_phone')->nullable();
            $table->string('clinic_phone')->nullable();
            $table->string('address')->nullable();
            $table->string('arabic_address')->nullable();
            $table->integer('detection_fee')->nullable();
            $table->integer('wiating_duration')->nullable();
            $table->timestamp('first_appointment_morning')->nullable();
            $table->timestamp('last_appointment_morning')->nullable();
            $table->integer('step_morning')->nullable();
            $table->integer('step_night')->nullable();
            $table->timestamp('first_appointment_night')->nullable();
            $table->timestamp('last_appointment_night')->nullable();
            $table->boolean('priority_from_here')->default(0);
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
        Schema::dropIfExists('doctors');
    }
}
