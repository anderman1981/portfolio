<?php

use App\Http\Controllers\Api\ChatReplyController;
use Illuminate\Support\Facades\Route;

// CV Chatbot ← Slack (n8n posts operator replies here)
Route::post('/chat-reply', [ChatReplyController::class, 'store']);
