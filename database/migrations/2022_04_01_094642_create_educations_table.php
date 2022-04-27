<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('educations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->nullableMorphs('country');
            $table->nullableMorphs('city');
            $table->longText('description')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('educations_has_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('education_id');
            $table->unsignedBigInteger('category_id');

            $table->foreign('education_id')
                ->references('id')
                ->on('educations')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('education_categories')
                ->onDelete('cascade');

            $table->primary(['education_id', 'category_id'], 'educations_has_categories_education_id_category_id_primary');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('educations_has_categories');
        Schema::dropIfExists('educations');
    }
}
