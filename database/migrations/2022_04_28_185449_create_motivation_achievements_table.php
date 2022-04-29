<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivationAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivation_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('bage_id')->constrained('motivation_bages', 'id')->cascadeOnDelete();
            $table->enum('type', ['one', 'unlimited'])->default('unlimited');
            $table->integer('count');
            $table->timestamp('time_limit')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('motivation_achievements');
    }
}
