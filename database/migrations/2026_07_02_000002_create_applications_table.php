<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_id')->nullable();
            $table->string('company');
            $table->string('position');
            $table->date('application_date');
            $table->string('status')->default('Postulado'); // Postulado, En Revisión, Entrevista, Oferta, Rechazado
            $table->string('score')->nullable(); // 4.8/5 (copied from evaluation)
            $table->string('link')->nullable(); // LinkedIn URL
            $table->text('notes')->nullable();
            $table->integer('interview_date_unix')->nullable(); // Próxima entrevista
            $table->integer('offer_date_unix')->nullable(); // Fecha de oferta
            $table->timestamps();
            $table->index(['company', 'application_date']);
            $table->index('status');
            $table->foreign('evaluation_id')->references('id')->on('evaluations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
