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
            $table->json('role')->nullable()->change();
            $table->json('location')->nullable()->change();
            $table->json('achievements')->nullable()->change();
        });

        Schema::table('skills', function (Blueprint $table) {
            $table->json('name')->nullable()->change();
            $table->json('category')->nullable()->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->json('title')->nullable()->change();
            $table->json('description')->nullable()->change();
        });

        Schema::table('education', function (Blueprint $table) {
            $table->json('degree')->nullable()->change();
            $table->json('institution')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
