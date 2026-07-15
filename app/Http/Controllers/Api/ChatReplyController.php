<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Reply-intake: n8n calls this when the operator answers from Slack.
 * The reply is stored against the conversation so the chatbot's poll picks it up.
 */
class ChatReplyController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Shared-secret auth — fail-closed: reject if token not configured or doesn't match.
        $expected = config('services.cv_bridge.token');
        if (! $expected || $request->header('X-Bridge-Token') !== $expected) {
            return response()->json(['ok' => false, 'error' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'conversation_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $msg = ChatMessage::create([
            'session_id' => $data['conversation_id'],
            'role' => 'assistant',
            'content' => $data['message'],
            'source' => 'operator',
        ]);

        return response()->json(['ok' => true, 'id' => $msg->id]);
    }
}
