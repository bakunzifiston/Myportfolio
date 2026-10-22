<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('icon', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_category_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['skill_category_id', 'name']);
            $table->index(['is_visible', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
        Schema::dropIfExists('skill_categories');
    }
};
