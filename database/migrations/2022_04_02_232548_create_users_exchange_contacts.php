<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersExchangeContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_exchange_contacts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->bigInteger('target_id')->nullable()->unsigned();
            $table->longText('message')->nullable();
            $table->string('status_id')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->nullable()->references('id')->on('users');
            $table->foreign('target_id')->nullable()->references('id')->on('users');
        });

        Schema::create('users_exchange_contacts_has_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exchange_id');
            $table->nullableMorphs('model');

            $table->foreign('exchange_id')
                ->references('id')
                ->on('users_exchange_contacts')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->primary(['exchange_id', 'user_id'], 'users_exchange_contacts_has_requests_exchange_id_user_id_primary');
        });

        Schema::create('users_exchange_contacts_has_sharing', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('exchange_id');
            $table->nullableMorphs('model');

            $table->foreign('exchange_id')
                ->references('id')
                ->on('users_exchange_contacts')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->primary(['exchange_id', 'user_id'], 'users_exchange_contacts_has_sharing_exchange_id_user_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_exchange_contacts_has_sharing');
        Schema::dropIfExists('users_exchange_contacts_has_requests');
        Schema::dropIfExists('users_exchange_contacts');
    }
}
