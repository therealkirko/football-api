<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('accuracy')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->time('clockin_time')->nullable();
            $table->time('clockout_time')->nullable();
            $table->boolean('status')->default(false);
            $table->foreignId('instore_id')->constrained();
            $table->boolean('hasPersonalPhoto')->default(false);
            $table->boolean('hasShelfPhoto')->default(false);
            $table->boolean('hasUpdatedStock')->default(false);
            $table->foreignId('ambassador_id')->constrained();
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
        Schema::dropIfExists('shifts');
    }
}
