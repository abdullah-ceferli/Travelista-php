<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->string('blog_img')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamp('pub_date')->index();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('subject');
            $table->unsignedTinyInteger('stars');
            $table->text('message');
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('newsletter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::create('chat_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('first_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('second_user_id')->constrained('users')->cascadeOnDelete();
            $table->unique(['first_user_id', 'second_user_id']);
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_thread_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['chat_messages', 'chat_threads', 'newsletter_subscriptions', 'contact_messages', 'comments', 'post_tag', 'tags', 'posts'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
