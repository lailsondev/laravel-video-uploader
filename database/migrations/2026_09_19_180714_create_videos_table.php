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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->uuid('code')->unique();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
