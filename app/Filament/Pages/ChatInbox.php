<?php

namespace App\Filament\Pages;

use App\Models\ChatMemory;
use App\Models\ChatMessage;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ChatInbox extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Conversaciones';

    protected static ?string $title = 'Bandeja de Conversaciones';

    protected string $view = 'filament.pages.chat-inbox';

    public ?string $selectedSession = null;

    public string $reply = '';

    public function mount(): void
    {
        // Deep link from the handoff notification: /amrTechAdmin/chat-inbox?session=XYZ
        if ($session = request()->query('session')) {
            $exists = ChatMessage::where('session_id', $session)->exists();
            if ($exists) {
                $this->selectedSession = $session;
            }
        }
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) ChatMessage::where('role', 'user')->distinct('session_id')->count('session_id');
    }

    /** Conversations: one row per session with lead + activity summary.
     *  Only sessions where the visitor actually wrote something (not just the welcome). */
    public function getConversationsProperty()
    {
        // Sessions that have at least one real user message.
        $realSessions = ChatMessage::where('role', 'user')->distinct()->pluck('session_id');

        $sessions = ChatMessage::selectRaw('session_id, COUNT(*) as total, MAX(created_at) as last_at')
            ->whereIn('session_id', $realSessions)
            ->groupBy('session_id')
            ->orderByDesc('last_at')
            ->limit(100)
            ->get();

        $memories = ChatMemory::whereIn('key', ['user_name', 'user_email'])
            ->get()
            ->groupBy('session_id');

        return $sessions->map(function ($s) use ($memories) {
            $mem = $memories->get($s->session_id, collect())->pluck('value', 'key');
            $last = ChatMessage::where('session_id', $s->session_id)->latest('id')->first();

            return [
                'session_id' => $s->session_id,
                'name' => $mem['user_name'] ?? 'Anónimo',
                'email' => $mem['user_email'] ?? '—',
                'total' => $s->total,
                'last_at' => $s->last_at,
                'preview' => \Illuminate\Support\Str::limit($last?->content, 40),
            ];
        });
    }

    /** Messages of the selected conversation. */
    public function getMessagesProperty()
    {
        if (! $this->selectedSession) {
            return collect();
        }

        return ChatMessage::where('session_id', $this->selectedSession)
            ->orderBy('id')
            ->get();
    }

    public function getLeadProperty(): array
    {
        if (! $this->selectedSession) {
            return ['name' => null, 'email' => null];
        }
        $mem = ChatMemory::where('session_id', $this->selectedSession)
            ->whereIn('key', ['user_name', 'user_email'])->pluck('value', 'key');

        return ['name' => $mem['user_name'] ?? 'Anónimo', 'email' => $mem['user_email'] ?? '—'];
    }

    public function selectConversation(string $session): void
    {
        $this->selectedSession = $session;
        $this->reply = '';
    }

    /** Reply as the operator; the visitor's chatbot polling will show it. */
    public function sendReply(): void
    {
        $this->validate(['reply' => 'required|string|max:2000']);

        ChatMessage::create([
            'session_id' => $this->selectedSession,
            'role' => 'assistant',
            'content' => $this->reply,
            'source' => 'operator',
        ]);

        $this->reply = '';

        Notification::make()->title('Respuesta enviada')->body('El visitante la verá en el chat.')->success()->send();
    }
}
