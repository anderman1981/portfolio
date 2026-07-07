<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('source');            // Remotive, RemoteOK, WeWorkRemotely, Jobicy, Himalayas
            $table->string('external_id')->nullable();
            $table->string('title');
            $table->string('company')->nullable();
            $table->text('url');
            $table->string('salary')->nullable();
            $table->string('job_type')->nullable();
            $table->string('location')->nullable();
            $table->text('tags')->nullable();
            $table->date('published_at')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_applied')->default(false);
            $table->boolean('is_dismissed')->default(false);
            $table->timestamps();

            $table->unique(['source', 'url']);
            $table->index('source');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
