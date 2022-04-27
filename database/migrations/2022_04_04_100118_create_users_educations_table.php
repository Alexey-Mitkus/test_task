<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_educations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('education_category_id')->nullable()->unsigned();
            $table->bigInteger('education_id')->nullable()->unsigned();
            $table->longText('raw_education')->nullable();
            $table->longText('position')->nullable();
            $table->longText('course_name')->nullable();
            $table->longText('course_organization')->nullable();
            $table->longText('course_link')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->nullable()->references('id')->on('users');
            $table->foreign('education_category_id')->nullable()->references('id')->on('education_categories');
            $table->foreign('education_id')->nullable()->references('id')->on('educations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_educations');
    }
}
