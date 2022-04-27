<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->string('type')->default('original');
            $table->integer('ordering')->nullable();
            $table->morphs('mediatable');
            $table->string('extension')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->longText('collection')->nullable();
            $table->longText('tag')->nullable();
            $table->longText('thumbnails')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('src')->nullable();
            $table->string('mimes')->nullable();
            $table->string('disk')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('folder')->nullable();
            $table->string('storage')->default('local');
            $table->longText('storage_data')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('media');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
