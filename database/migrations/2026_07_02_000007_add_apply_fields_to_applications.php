<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedTinyInteger('fit_score')->nullable()->after('score'); // 0-100 from /apply
            $table->string('cv_path')->nullable()->after('fit_score');      // e.g. main_leadtech
            $table->string('cover_path')->nullable()->after('cv_path');     // e.g. cover_leadtech_ai_native_developer
            $table->text('evaluation_notes')->nullable()->after('notes');   // fit evaluation summary
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['fit_score', 'cv_path', 'cover_path', 'evaluation_notes']);
        });
    }
};
