<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersManagmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_managments', function (Blueprint $table) {
            $table->id();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->longText('getting')->nullable();
            $table->longText('share')->nullable();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('role_id')->nullable()->unsigned();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->nullable()->references('id')->on('users');
            $table->foreign('role_id')->nullable()->references('id')->on('users_managments_roles');
        });

        Schema::create('users_managments_has_methodologies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('managment_id');
            $table->longText('value')->nullable();

            $table->foreign('managment_id')
                ->references('id')
                ->on('users_managments')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['managment_id', 'user_id'], 'users_managments_has_methodologies_managment_id_user_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_managments_has_methodologies');
        Schema::dropIfExists('users_managments');
    }
}
