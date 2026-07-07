<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AITools extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'Docs & AI Tools';

    protected static ?string $title = 'Centro de Inteligencia y Documentación';

    protected string $view = 'filament.pages.a-i-tools';

    public function getHeaderActions(): array
    {
        return [
            Action::make('security_audit')
                ->label('Ejecutar Auditoría de Seguridad (LaraClaude Style)')
                ->color('danger')
                ->icon('heroicon-o-shield-check')
                ->action(fn () => $this->runAudit('security')),

            Action::make('seo_audit')
                ->label('Optimización SEO')
                ->color('success')
                ->icon('heroicon-o-magnifying-glass')
                ->action(fn () => $this->runAudit('seo')),
        ];
    }

    private function runAudit($type)
    {
        $apiKey = config('services.gemini.key');
        if (! $apiKey) {
            Notification::make()->title('Error')->body('Gemini API Key no configurada.')->danger()->send();

            return;
        }

        $prompt = $type === 'security'
            ? 'Analiza este proyecto Laravel y detecta posibles fallos de seguridad comunes.'
            : 'Analiza este portafolio y sugiere mejoras de SEO y visibilidad.';

        // Simulate a call for now as a demo of the integrated skill
        Notification::make()
            ->title('Auditoría Iniciada')
            ->body("Utilizando la lógica de 'laraclaude' para procesar la solicitud de {$type}...")
            ->success()
            ->send();
    }
}
