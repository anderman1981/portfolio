<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_memories', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('key'); // e.g., 'user_name', 'topic_of_interest'
            $table->text('value');
            $table->float('importance')->default(1.0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_memories');
    }
};
