<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKbPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kb_posts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->string('lang')->default('rus');
            $table->string('status_id')->nullable();
            $table->bigInteger('views')->default(0);
            $table->string('is_active')->default(1);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('slug')->unique()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('kb_posts_has_themes', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('theme_id');

            $table->foreign('post_id')
                ->references('id')
                ->on('kb_posts')
                ->onDelete('cascade');

            $table->foreign('theme_id')
                ->references('id')
                ->on('kb_themes')
                ->onDelete('cascade');

            $table->primary(['post_id', 'theme_id'], 'kb_posts_has_themes_post_id_theme_id_primary');
        });

        Schema::create('kb_posts_has_formats', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('format_id');

            $table->foreign('post_id')
                ->references('id')
                ->on('kb_posts')
                ->onDelete('cascade');

            $table->foreign('format_id')
                ->references('id')
                ->on('kb_formats')
                ->onDelete('cascade');

            $table->primary(['post_id', 'format_id'], 'kb_posts_has_formats_post_id_format_id_primary');
        });

        Schema::create('kb_posts_has_likes', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('post_id')
                ->references('id')
                ->on('kb_posts')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->primary(['post_id', 'user_id'], 'kb_posts_has_likes_post_id_user_id_primary');
        });

        Schema::create('kb_posts_has_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');

            $table->foreign('post_id')
                ->references('id')
                ->on('kb_posts')
                ->onDelete('cascade');

            $table->foreign('tag_id')
                ->references('id')
                ->on('kb_tags')
                ->onDelete('cascade');

            $table->primary(['post_id', 'tag_id'], 'kb_posts_has_tags_post_id_tag_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kb_posts_has_tags');
        Schema::dropIfExists('kb_posts_has_likes');
        Schema::dropIfExists('kb_posts_has_formats');
        Schema::dropIfExists('kb_posts_has_themes');
        Schema::dropIfExists('kb_posts');
    }
}
