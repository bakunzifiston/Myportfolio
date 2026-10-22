<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            // "employment" for roles with an organisation, "project" for project-based contributions
            $table->string('type', 20)->default('employment');
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('organization', 150)->nullable();
            $table->string('position', 150)->nullable();
            $table->string('employment_type', 50)->nullable();
            $table->string('location', 150)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('summary')->nullable();
            $table->json('responsibilities')->nullable();
            $table->json('contributions')->nullable();
            $table->json('technologies')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'type', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
