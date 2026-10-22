<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 50)->unique();
            $table->string('label', 100);
            $table->string('url')->nullable();
            $table->string('icon', 50)->nullable();
            $table->boolean('is_visible')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_visible', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
