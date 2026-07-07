<?php

namespace App\Livewire;

use App\Models\ChatMemory;
use App\Models\ChatMessage;
use App\Models\Experience;
use App\Models\Repository;
use App\Models\Skill;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class AIChat extends Component
{
    public $messages = [];

    public $question = '';

    public $is_loading = false;

    protected $sessionId;

    public function mount()
    {
        $this->sessionId = Session::getId();
        $this->loadHistory();

        if (empty($this->messages)) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => __('portfolio.chat.welcome'),
            ];
            $this->saveMessage('assistant', __('portfolio.chat.welcome'));
        }
    }

    private function loadHistory()
    {
        $history = ChatMessage::where('session_id', Session::getId())
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($history as $msg) {
            $this->messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }
    }

    private function saveMessage($role, $content)
    {
        ChatMessage::create([
            'session_id' => Session::getId(),
            'role' => $role,
            'content' => $content,
        ]);
    }

    public function ask()
    {
        $this->validate([
            'question' => 'required|string|min:3|max:500',
        ]);

        $userQuestion = $this->question;
        $this->messages[] = ['role' => 'user', 'content' => $userQuestion];
        $this->saveMessage('user', $userQuestion);

        $this->question = '';
        $this->is_loading = true;

        $context = $this->getCVContext();
        $memories = $this->getMemories();

        try {
            $apiKey = config('services.gemini.key');

            if (! $apiKey || $apiKey === 'your_gemini_api_key_here') {
                $errorMsg = app()->getLocale() === 'es'
                    ? 'Lo siento, la API Key de Gemini no está configurada.'
                    : 'I am sorry, the Gemini API Key is not configured.';
                $this->messages[] = ['role' => 'assistant', 'content' => $errorMsg];
                $this->is_loading = false;

                return;
            }

            // Build conversation history for the prompt (last 10 messages)
            $historyPrompt = '';
            $recentMessages = array_slice($this->messages, -11, 10);
            foreach ($recentMessages as $msg) {
                $historyPrompt .= ucfirst($msg['role']).': '.$msg['content']."\n";
            }

            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $this->getSystemPrompt($context, $memories)."\n\nCONVERSATION HISTORY:\n{$historyPrompt}\n\nUser Question: ".$userQuestion],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text');
                $this->messages[] = ['role' => 'assistant', 'content' => $content];
                $this->saveMessage('assistant', $content);

                // Distill memory in the background (mocking the agentmemory logic)
                $this->distillMemory($userQuestion, $content);
            } else {
                $errorMsg = app()->getLocale() === 'es'
                    ? 'Hubo un error al comunicarme con mi cerebro de IA.'
                    : 'There was an error communicating with my AI brain.';
                $this->messages[] = ['role' => 'assistant', 'content' => $errorMsg];
            }
        } catch (\Exception $e) {
            $this->messages[] = ['role' => 'assistant', 'content' => 'Error: '.$e->getMessage()];
        }

        $this->is_loading = false;
    }

    private function getMemories()
    {
        return ChatMemory::where('session_id', Session::getId())
            ->get()
            ->map(fn ($m) => "- {$m->key}: {$m->value}")
            ->implode("\n");
    }

    private function distillMemory($question, $answer)
    {
        // Simple logic to extract user facts (inspired by agentmemory)
        // In a real scenario, we would call LLM again to "extract facts"
        if (str_contains(strtolower($question), 'mi nombre es')) {
            $parts = explode('es', strtolower($question));
            $name = trim(end($parts));
            ChatMemory::updateOrCreate(
                ['session_id' => Session::getId(), 'key' => 'user_name'],
                ['value' => ucfirst($name)]
            );
        }
    }

    private function getCVContext()
    {
        $locale = app()->getLocale();
        $experiences = Experience::all()->map(function ($e) use ($locale) {
            $achievements = collect($e->getTranslation('achievements', $locale))->map(fn ($a) => "  * {$a}")->implode("\n");

            return "- {$e->getTranslation('role', $locale)} en {$e->company} ({$e->start_date} - {$e->end_date})\n{$achievements}";
        })->implode("\n\n");

        $skills = Skill::all()->map(fn ($s) => "- {$s->getTranslation('name', $locale)} ({$s->proficiency}%)")->implode("\n");
        $repositories = Repository::where('is_visible', true)->get()->map(fn ($r) => "- {$r->getTranslation('name', $locale)}: {$r->url}")->implode("\n");

        return "RESUME DATA:\n{$experiences}\n\nSKILLS:\n{$skills}\n\nREPOS:\n{$repositories}";
    }

    private function getSystemPrompt($context, $memories)
    {
        $locale = app()->getLocale();
        $lang = $locale === 'es' ? 'ESPAÑOL' : 'ENGLISH';
        $memoryContext = $memories ? "\nTHINGS I REMEMBER ABOUT THIS USER:\n{$memories}" : '';

        $designSystem = '';
        if (file_exists(base_path('../DESIGN.md'))) {
            $designSystem = "\nVISUAL IDENTITY (DESIGN.md):\n".file_get_contents(base_path('../DESIGN.md'));
        }

        return "Act as Anderson's Virtual Assistant. Respond in {$lang}.
        Goal: Answer recruiter questions professionally based on the context.
        {$memoryContext}
        {$designSystem}
        
        CONTEXT:\n{$context}";
    }

    public function render()
    {
        return view('livewire.a-i-chat');
    }
}
