<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('tags');      // plain text, HTML stripped
            $table->unsignedTinyInteger('score')->nullable()->after('description'); // 0-100 pre-score (JobScorer)
            $table->json('score_breakdown')->nullable()->after('score');
            $table->boolean('eligible_region')->nullable()->after('score_breakdown'); // true / false / null = unknown
            $table->string('dedupe_hash', 40)->nullable()->after('eligible_region');
            $table->foreignId('duplicate_of')->nullable()->after('dedupe_hash')
                ->constrained('job_listings')->nullOnDelete();

            $table->index('score');
            $table->index('dedupe_hash');
        });
    }

    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('duplicate_of');
            $table->dropIndex(['score']);
            $table->dropIndex(['dedupe_hash']);
            $table->dropColumn(['description', 'score', 'score_breakdown', 'eligible_region', 'dedupe_hash']);
        });
    }
};
