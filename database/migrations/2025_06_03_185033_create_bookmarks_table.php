<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('item_id')->nullable()->unique();
            $table->string('resolved_id')->nullable();
            $table->string('given_url')->nullable();
            $table->string('given_title')->nullable();
            $table->string('resolved_url')->nullable();
            $table->string('resolved_title')->nullable();
            $table->string('favorite')->nullable();
            $table->text('excerpt')->nullable();
            $table->boolean('is_article')->nullable();
            $table->boolean('has_video')->nullable();
            $table->boolean('has_image')->nullable();
            $table->integer('word_count')->nullable();
            $table->string('lang')->nullable();
            $table->timestamp('time_added')->nullable();
            $table->timestamp('time_updated')->nullable();
            $table->timestamp('time_read')->nullable();
            $table->timestamp('time_favorited')->nullable();
            $table->string('status')->default('published');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
