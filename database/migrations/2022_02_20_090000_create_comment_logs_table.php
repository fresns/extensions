<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->unsignedTinyInteger('platform_id');
            $table->unsignedTinyInteger('is_plugin_editor')->default('0');
            $table->string('editor_unikey', 64)->nullable();
            $table->string('types', 128)->default('text')->index('types');
            $table->longText('content')->nullable();
            $table->unsignedTinyInteger('is_markdown')->default('0');
            $table->unsignedTinyInteger('is_anonymous')->default('0');
            $table->json('location_json')->nullable();
            $table->json('files_json')->nullable();
            $table->json('extends_json')->nullable();
            $table->unsignedTinyInteger('state')->default('1');
            $table->string('reason')->nullable();
            $table->timestamp('submit_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_logs');
    }
}
