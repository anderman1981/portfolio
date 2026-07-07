<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_id');
            $table->string('type'); // cover, summary, report
            $table->text('content'); // Markdown content
            $table->string('external_path')->nullable(); // ../output/XXX-COVER-LETTER.md
            $table->timestamps();
            $table->unique(['evaluation_id', 'type']);
            $table->foreign('evaluation_id')->references('id')->on('evaluations')->cascadeOnDelete();
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
