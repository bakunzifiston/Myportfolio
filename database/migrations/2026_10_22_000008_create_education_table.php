<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('level', 30);
            $table->string('field_of_study', 150)->nullable();
            // Formal title (e.g. "BSc Computer Science"); only shown publicly once the status is "awarded"
            $table->string('qualification_title', 150)->nullable();
            $table->string('institution', 200)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->unsignedSmallInteger('start_year')->nullable();
            $table->unsignedSmallInteger('end_year')->nullable();
            // awarded | in_progress | pursued
            $table->string('status', 20)->default('pursued');
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
