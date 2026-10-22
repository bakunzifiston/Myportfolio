<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 150);
            $table->string('slug', 170)->unique();
            $table->string('short_description', 300)->nullable();
            $table->text('overview')->nullable();
            $table->string('role', 150)->nullable();
            $table->text('contribution')->nullable();
            $table->json('technologies')->nullable();
            $table->json('key_features')->nullable();
            $table->text('challenges')->nullable();
            $table->text('solutions')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('thumbnail_alt')->nullable();
            $table->json('screenshots')->nullable();
            $table->string('live_url')->nullable();
            $table->string('repository_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status', 20)->default('draft');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
