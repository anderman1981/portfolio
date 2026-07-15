<?php

use App\Http\Controllers\Api\ChatReplyController;
use Illuminate\Support\Facades\Route;

// CV Chatbot ← Slack (n8n posts operator replies here)
// 60/min is generous for n8n but blocks flooding
Route::middleware('throttle:60,1')->post('/chat-reply', [ChatReplyController::class, 'store']);
