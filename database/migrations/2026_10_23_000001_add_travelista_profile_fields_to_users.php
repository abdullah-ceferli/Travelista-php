<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('location')->nullable();
            $table->string('contact_email')->nullable();
            $table->text('about_me')->nullable();
            $table->string('profile_img')->nullable();
            $table->string('google_id')->nullable()->unique();
            $table->boolean('is_admin')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'first_name', 'last_name', 'age', 'location', 'contact_email', 'about_me', 'profile_img', 'google_id', 'is_admin']);
        });
    }
};
