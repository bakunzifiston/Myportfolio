<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('issuer', 150)->nullable();
            $table->date('issued_on')->nullable();
            $table->date('expires_on')->nullable();
            $table->string('credential_id', 120)->nullable();
            $table->string('credential_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
