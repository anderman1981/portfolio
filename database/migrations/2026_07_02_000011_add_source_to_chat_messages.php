<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('chat_messages', function (Blueprint $t) {
            $t->string('source')->default('app')->after('content'); // app | operator (Slack)
            $t->boolean('bridged')->default(false)->after('source'); // ya reenviado al bridge
        });
    }
    public function down(): void {
        Schema::table('chat_messages', function (Blueprint $t) {
            $t->dropColumn(['source','bridged']);
        });
    }
};
