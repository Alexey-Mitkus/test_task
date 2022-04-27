<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->nullableMorphs('country');
            $table->nullableMorphs('region');
            $table->decimal('lat', 8, 6)->nullable();
            $table->decimal('lng', 8, 6)->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
