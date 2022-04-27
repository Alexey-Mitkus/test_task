<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersFiledsTable extends Migration
{
    public function up()
    {
        Schema::create('users_fileds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type_id')->nullable();
            $table->longText('options')->nullable();
            $table->string('group_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('users_fileds_has_users', function (Blueprint $table) {
            $table->unsignedBigInteger('field_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('value')->nullable();
            $table->bigInteger('points')->nullable();
            $table->string('is_show')->default(1);

            $table->foreign('field_id')
                ->references('id')
                ->on('users_fileds');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->primary(['field_id', 'user_id'], 'users_fileds_has_users_field_id_user_id_primary');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_fileds_has_users');
        Schema::dropIfExists('users_fileds');
    }
}
