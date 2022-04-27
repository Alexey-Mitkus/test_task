<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_jobs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('organization_id')->nullable()->unsigned();
            $table->longText('raw_organization')->nullable();
            $table->longText('position')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->nullable()->references('id')->on('users');
        });

        Schema::create('users_jobs_has_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_id');
            $table->longText('value')->nullable();

            $table->foreign('job_id')
                ->references('id')
                ->on('users_jobs')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            // $table->primary(['job_id', 'value'], 'users_jobs_has_tags_job_id_value_primary');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_jobs_has_tags');
        Schema::dropIfExists('users_jobs');
    }
}
