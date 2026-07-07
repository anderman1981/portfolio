<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique()->nullable(); // ID desde career-ops
            $table->integer('rank')->nullable(); // #1, #2, #3...
            $table->date('evaluation_date');
            $table->string('company');
            $table->string('position');
            $table->string('score')->nullable(); // 4.8/5
            $table->string('status')->default('evaluated'); // evaluated, applied, offer, rejected
            $table->string('jd_url')->nullable();
            $table->text('archetype')->nullable(); // Role archetype
            $table->text('domain')->nullable(); // Domain info
            $table->text('function')->nullable(); // Function info
            $table->json('requirements')->nullable(); // Parsed from JD
            $table->json('match_analysis')->nullable(); // Block B
            $table->json('level_strategy')->nullable(); // Block C
            $table->json('compensation_analysis')->nullable(); // Block D
            $table->json('interview_prep')->nullable(); // Block F (STAR stories)
            $table->text('legitimacy_assessment')->nullable(); // Block G
            $table->timestamps();
            $table->index(['company', 'evaluation_date']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
