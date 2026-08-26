<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->index();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->string('type')->default('post')->index(); $table->timestamps();
        });
        Schema::create('tags', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->timestamps();
        });
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title'); $table->string('slug')->unique(); $table->text('excerpt'); $table->longText('content'); $table->string('cover_image')->nullable();
            $table->string('status')->default('draft')->index(); $table->boolean('is_featured')->default(false)->index(); $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable(); $table->string('seo_description', 320)->nullable(); $table->timestamps(); $table->softDeletes();
            $table->index(['status', 'published_at']);
        });
        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete(); $table->foreignId('tag_id')->constrained()->cascadeOnDelete(); $table->primary(['post_id', 'tag_id']);
        });
        Schema::create('technologies', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->unsignedSmallInteger('sort_order')->default(0); $table->timestamps();
        });
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); $table->string('name'); $table->string('slug')->unique();
            $table->text('summary'); $table->longText('content'); $table->string('cover_image')->nullable(); $table->string('status')->default('draft')->index();
            $table->string('project_status')->default('selesai'); $table->unsignedSmallInteger('year')->index(); $table->string('role')->nullable(); $table->string('demo_url')->nullable(); $table->string('repository_url')->nullable();
            $table->boolean('is_featured')->default(false)->index(); $table->unsignedSmallInteger('sort_order')->default(0)->index(); $table->timestamp('published_at')->nullable()->index();
            $table->string('seo_title')->nullable(); $table->string('seo_description', 320)->nullable(); $table->timestamps(); $table->softDeletes(); $table->index(['status', 'published_at']);
        });
        Schema::create('project_technology', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->cascadeOnDelete(); $table->foreignId('technology_id')->constrained()->cascadeOnDelete(); $table->primary(['project_id', 'technology_id']);
        });
        Schema::create('project_images', function (Blueprint $table) {
            $table->id(); $table->foreignId('project_id')->constrained()->cascadeOnDelete(); $table->string('path'); $table->string('alt_text')->nullable(); $table->unsignedSmallInteger('sort_order')->default(0); $table->timestamps();
        });
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email'); $table->string('subject')->nullable(); $table->text('message'); $table->timestamp('read_at')->nullable()->index(); $table->timestamps(); $table->softDeletes();
        });
        Schema::create('settings', function (Blueprint $table) {
            $table->id(); $table->string('key')->unique(); $table->text('value')->nullable(); $table->string('group')->default('general')->index(); $table->timestamps();
        });
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $table->string('action'); $table->string('subject_type')->nullable(); $table->unsignedBigInteger('subject_id')->nullable(); $table->json('context')->nullable(); $table->timestamps(); $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs'); Schema::dropIfExists('settings'); Schema::dropIfExists('contact_messages'); Schema::dropIfExists('project_images'); Schema::dropIfExists('project_technology'); Schema::dropIfExists('projects'); Schema::dropIfExists('technologies'); Schema::dropIfExists('post_tag'); Schema::dropIfExists('posts'); Schema::dropIfExists('tags'); Schema::dropIfExists('categories');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_admin'));
    }
};
