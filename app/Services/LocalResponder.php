<?php

namespace App\Services;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;

/**
 * Offline fallback responder. When Gemini is unavailable (quota/errors),
 * this answers common questions using ONLY the system DB + preset templates,
 * in Anderson's voice. No external calls, no model download — always available.
 */
class LocalResponder
{
    /** Intent → keyword map (ES + EN). Order matters: first match wins. */
    protected array $intents = [
        'contact' => ['contact', 'contacto', 'email', 'correo', 'teléfono', 'telefono', 'llamar', 'escribir', 'contratar', 'hire', 'reach', 'cotiza', 'presupuesto'],
        'availability' => ['disponib', 'available', 'remoto', 'remote', 'freelance', 'vacante', 'empleo', 'contrataci', 'jornada'],
        'ai' => ['inteligencia artificial', ' ia ', ' ai ', 'automatiz', 'gemini', 'openai', 'nlp', 'machine learning', 'chatbot', 'agente'],
        'skills' => ['skill', 'habilidad', 'tecnolog', 'lenguaje', 'stack', 'php', 'laravel', 'python', 'node', 'react', 'angular', 'javascript', 'backend', 'frontend', 'framework', 'sabes', 'dominas', 'conoces'],
        'projects' => ['proyecto', 'project', 'portfolio', 'portafolio', 'has hecho', 'construido', 'desarrollado', 'built', 'trabajado en'],
        'experience' => ['experiencia', 'experience', 'trabajaste', 'trayectoria', 'años', 'empresas', 'career', 'worked', 'background', 'quién eres', 'quien eres', 'cuéntame', 'cuentame', 'about you'],
        'greeting' => ['hola', 'buenas', 'buenos días', 'buenas tardes', 'hi', 'hello', 'hey', 'qué tal', 'que tal'],
    ];

    public function respond(string $message, string $locale = 'es'): string
    {
        $intent = $this->detect($message);
        $es = $locale === 'es';

        return match ($intent) {
            'greeting' => $es
                ? '¡Hola! 👋 Soy el asistente de Anderson. Puedo contarte sobre su experiencia, sus habilidades técnicas, sus proyectos o cómo contactarlo. ¿Qué te gustaría saber?'
                : 'Hi! 👋 I\'m Anderson\'s assistant. I can tell you about his experience, technical skills, projects, or how to reach him. What would you like to know?',

            'skills' => $this->skills($es),
            'experience' => $this->experience($es),
            'projects' => $this->projects($es),
            'ai' => $this->ai($es),
            'availability' => $es
                ? 'Anderson está abierto a nuevas oportunidades remotas. Es Líder Técnico y Full Stack con 17 años de experiencia, basado en Colombia (UTC-5) y acostumbrado a trabajar con equipos de US y Europa. Si quieres avanzar, escríbele a andersonmares81@gmail.com o dime "hablar con Anderson" y te conecto. 🙌'
                : 'Anderson is open to new remote opportunities. He\'s a Technical Lead & Full Stack developer with 17 years of experience, based in Colombia (UTC-5) and used to working with US and EU teams. To move forward, email andersonmares81@gmail.com or type "talk to Anderson" and I\'ll connect you. 🙌',
            'contact' => $es
                ? 'Con gusto. 📬 Puedes escribirle a **andersonmares81@gmail.com** o al **+57 316 826 5737**. También está en LinkedIn. Si prefieres, escribe "hablar con Anderson" y te conecto directamente por aquí.'
                : 'Sure! 📬 You can reach him at **andersonmares81@gmail.com** or **+57 316 826 5737**. He\'s also on LinkedIn. If you prefer, type "talk to Anderson" and I\'ll connect you directly here.',

            default => $es
                ? 'Puedo contarte sobre la experiencia de Anderson, sus habilidades (Node.js, Python, PHP/Laravel, IA), sus proyectos o cómo contactarlo. ¿Sobre cuál te gustaría saber? Y si prefieres hablar con él directo, escribe "hablar con Anderson". 😊'
                : 'I can tell you about Anderson\'s experience, his skills (Node.js, Python, PHP/Laravel, AI), his projects, or how to reach him. Which one would you like? And if you\'d rather talk to him directly, type "talk to Anderson". 😊',
        };
    }

    protected function detect(string $message): string
    {
        // Normalize punctuation to spaces so "IA?" or "Laravel." still match.
        $t = ' '.preg_replace('/[^\p{L}\p{N}]+/u', ' ', mb_strtolower($message)).' ';
        foreach ($this->intents as $intent => $keywords) {
            foreach ($keywords as $k) {
                if (str_contains($t, $k)) {
                    return $intent;
                }
            }
        }

        return 'unknown';
    }

    protected function skills(bool $es): string
    {
        $skills = Skill::all()
            ->groupBy('category')
            ->map(fn ($group, $cat) => $cat.': '.$group->take(5)->map(fn ($s) => $this->flat($s->getTranslation('name', app()->getLocale())))->implode(', '))
            ->take(4)
            ->implode(' · ');

        if (! $skills) {
            $skills = 'Node.js, Python, PHP/Laravel, React, IA (Gemini/OpenAI)';
        }

        return $es
            ? "Anderson maneja un stack amplio tras 17 años: {$skills}. ¿Quieres que profundice en alguna tecnología en particular?"
            : "Anderson has a broad stack from 17 years: {$skills}. Want me to go deeper on any specific technology?";
    }

    protected function experience(bool $es): string
    {
        $recent = Experience::orderByRaw('start_date IS NULL, start_date DESC')->take(3)->get()
            ->map(fn ($e) => $this->flat($e->getTranslation('role', app()->getLocale())).' @ '.$e->company)
            ->implode('; ');

        return $es
            ? "Anderson es Líder Técnico y Desarrollador Full Stack con 17 años en TI. Sus roles recientes: {$recent}. Ha liderado equipos, integrado sistemas y construido soluciones con IA. ¿Quieres que te cuente de alguno en detalle?"
            : "Anderson is a Technical Lead & Full Stack Developer with 17 years in IT. Recent roles: {$recent}. He has led teams, integrated systems and built AI-powered solutions. Want details on any of them?";
    }

    protected function projects(bool $es): string
    {
        $projects = Project::take(3)->get()
            ->map(fn ($p) => '• '.$p->title)
            ->implode("\n");

        return $es
            ? "Algunos proyectos de Anderson:\n{$projects}\n¿Quieres que te cuente más de alguno?"
            : "Some of Anderson's projects:\n{$projects}\nWant to hear more about any of them?";
    }

    protected function ai(bool $es): string
    {
        return $es
            ? 'La IA es una de las fortalezas de Anderson: ha construido un ATS con análisis de sentimiento y detección de nivel de inglés desde video, integraciones con Gemini y OpenAI, bots inteligentes y automatizaciones. Combina modelos de lenguaje con sistemas reales para que "hagan cosas", no solo respondan. ¿Te interesa algún caso puntual?'
            : 'AI is one of Anderson\'s strengths: he built an ATS with sentiment analysis and English-level detection from video, Gemini and OpenAI integrations, smart bots and automations. He combines language models with real systems so they "do things", not just answer. Interested in a specific case?';
    }

    /** Translatable fields may come back as arrays; flatten to a string. */
    protected function flat($value): string
    {
        if (is_array($value)) {
            return collect($value)->flatten()->filter(fn ($v) => is_scalar($v))->implode(' ');
        }

        return (string) $value;
    }
}
