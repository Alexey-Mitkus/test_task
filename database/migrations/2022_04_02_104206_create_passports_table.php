<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('organization_id')->nullable()->unsigned();
            $table->longText('raw_organization')->nullable();
            $table->bigInteger('category_id')->nullable()->unsigned();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->longText('description')->nullable();
            $table->longText('prerequisite')->nullable();
            $table->longText('vision')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->boolean('is_complete')->default(0);
            $table->string('step')->nullable();
            $table->string('status')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->nullable()->references('id')->on('users');
            $table->foreign('category_id')->nullable()->references('id')->on('passports_categories');
            $table->foreign('organization_id')->nullable()->references('id')->on('organizations');
        });

        Schema::create('passports_has_directors', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_directors_passport_id_user_id_primary');
        });

        Schema::create('passports_has_teams', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_teams_passport_id_user_id_primary');
        });

        Schema::create('passports_has_interests', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();
            $table->string('role')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_interests_passport_id_user_id_primary');
        });

        Schema::create('passports_has_objectives', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_objectives_passport_id_user_id_primary');
        });

        Schema::create('passports_has_results', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_results_passport_id_user_id_primary');
        });

        Schema::create('passports_has_metrics', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();
            $table->string('value')->nullable();
            $table->string('before')->nullable();
            $table->string('after')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_metrics_passport_id_user_id_primary');
        });

        Schema::create('passports_has_resources', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();
            $table->string('value')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_resources_passport_id_user_id_primary');
        });

        Schema::create('passports_has_budgets', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();
            $table->string('value')->nullable();
            $table->string('type_id')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_budgets_passport_id_user_id_primary');
        });

        Schema::create('passports_has_risks', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();

            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_risks_passport_id_user_id_primary');
        });

        Schema::create('passports_has_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('passport_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('name')->nullable();
            $table->longText('description')->nullable();
            $table->timestamp('date')->nullable();
            
            $table->foreign('passport_id')
                ->references('id')
                ->on('passports');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['passport_id', 'user_id'], 'passports_has_plans_passport_id_user_id_primary');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passports_has_plans');
        Schema::dropIfExists('passports_has_risks');
        Schema::dropIfExists('passports_has_budgets');
        Schema::dropIfExists('passports_has_resources');
        Schema::dropIfExists('passports_has_metrics');
        Schema::dropIfExists('passports_has_results');
        Schema::dropIfExists('passports_has_objectives');
        Schema::dropIfExists('passports_has_interests');
        Schema::dropIfExists('passports_has_teams');
        Schema::dropIfExists('passports_has_directors');
        Schema::dropIfExists('passports');
    }
}
