<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_log', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('user');
            $table->nullableMorphs('target');
            $table->bigInteger('category_id')->nullable()->unsigned();
            $table->string('event_type')->nullable();
            $table->longText('old_values')->nullable();
            $table->longText('new_values')->nullable();
            $table->unsignedBigInteger('processed_user_id')->nullable();
            $table->longText('message')->nullable();
            $table->string('status_id')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('category_id')->nullable()->references('id')->on('events_log_categories');
            $table->foreign('processed_user_id')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_log');
    }
}
