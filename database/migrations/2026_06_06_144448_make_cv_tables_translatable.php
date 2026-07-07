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
        Schema::table('experiences', function (Blueprint $table) {
            $table->json('role')->change();
            $table->json('location')->change();
            // Achievements is already json, but will store nested translations
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->json('name')->change();
            $table->json('category')->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('description')->change();
            // technologies is already json
        });

        Schema::table('education', function (Blueprint $table) {
            $table->json('degree')->change();
            $table->json('institution')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('role')->change();
            $table->string('location')->change();
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('category')->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('title')->change();
            $table->text('description')->change();
        });

        Schema::table('education', function (Blueprint $table) {
            $table->string('degree')->change();
            $table->string('institution')->change();
        });
    }
};
