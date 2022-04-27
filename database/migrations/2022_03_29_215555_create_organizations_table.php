<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('abbreviation');
            $table->bigInteger('country_id')->nullable()->unsigned();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->longText('address')->nullable();
            $table->longText('raw_country')->nullable();
            $table->longText('raw_city')->nullable();
            $table->longText('raw_full_address')->nullable();
            $table->decimal('lat', 8, 6)->nullable();
            $table->decimal('lng', 8, 6)->nullable();
            $table->longText('description')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('country_id')->nullable()->references('id')->on('countries');
            $table->foreign('city_id')->nullable()->references('id')->on('cities');
        });

        Schema::create('organizations_has_types', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('type_id');

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');

            $table->foreign('type_id')
                ->references('id')
                ->on('organizations_types')
                ->onDelete('cascade');

            $table->primary(['organization_id', 'type_id'], 'organizations_has_types_organization_id_type_id_primary');
        });

        Schema::create('organizations_has_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('category_id');

            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('organizations_categories')
                ->onDelete('cascade');

            $table->primary(['organization_id', 'category_id'], 'organizations_has_categories_organization_id_category_id_primary');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations_has_types');
        Schema::dropIfExists('organizations_has_categories');
        Schema::dropIfExists('organizations');
    }
}
