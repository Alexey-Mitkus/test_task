<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTypesTable extends Migration
{
    public function up()
    {
        Schema::create('organizations_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->longText('description')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('organizations_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations_types');
    }
}
