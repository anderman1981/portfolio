<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_portals', function (Blueprint $table) {
            $table->string('auth_type')->default('none')->comment('none, email, google, github, linkedin, etc');
            $table->string('email')->nullable()->comment('Email or username for login');
            $table->text('password')->nullable()->comment('Encrypted password');
            $table->text('api_key')->nullable()->comment('API key if applicable');
            $table->json('additional_data')->nullable()->comment('Extra fields like 2FA, security questions, etc');
            $table->timestamp('last_login')->nullable();
            $table->boolean('active')->default(true)->comment('Is this portal active for automation');
        });
    }

    public function down(): void
    {
        Schema::table('job_portals', function (Blueprint $table) {
            $table->dropColumn([
                'auth_type',
                'email',
                'password',
                'api_key',
                'additional_data',
                'last_login',
                'active',
            ]);
        });
    }
};
