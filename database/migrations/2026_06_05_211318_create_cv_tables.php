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
        Schema::create('experiences', function (Blueprint $link) {
            $link->id();
            $link->string('company');
            $link->string('role');
            $link->string('location')->nullable();
            $link->string('start_date');
            $link->string('end_date')->nullable();
            $link->json('achievements');
            $link->boolean('is_current')->default(false);
            $link->timestamps();
        });

        Schema::create('skills', function (Blueprint $link) {
            $link->id();
            $link->string('name');
            $link->string('category'); // AI, Backend, Frontend, etc.
            $link->integer('proficiency')->default(100); // 0-100
            $link->string('icon')->nullable();
            $link->timestamps();
        });

        Schema::create('projects', function (Blueprint $link) {
            $link->id();
            $link->string('title');
            $link->text('description');
            $link->json('technologies');
            $link->string('url')->nullable();
            $link->string('image_path')->nullable();
            $link->timestamps();
        });

        Schema::create('education', function (Blueprint $link) {
            $link->id();
            $link->string('degree');
            $link->string('institution');
            $link->string('year');
            $link->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('experiences');
    }
};
