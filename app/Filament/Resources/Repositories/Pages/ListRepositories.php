<?php

namespace App\Filament\Resources\Repositories\Pages;

use App\Filament\Resources\Repositories\RepositoryResource;
use App\Models\Repository;
use App\Services\GitHubService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListRepositories extends ListRecords
{
    protected static string $resource = RepositoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sync_github')
                ->label('Sincronizar GitHub')
                ->color('info')
                ->icon('heroicon-o-arrow-path')
                ->action(function (GitHubService $gitHubService) {
                    $repos = $gitHubService->getUserRepositories();

                    if (empty($repos)) {
                        Notification::make()
                            ->title('Error')
                            ->body('No se pudieron obtener los repositorios. Verifica tu GITHUB_TOKEN y GITHUB_USERNAME en el .env.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $count = 0;
                    foreach ($repos as $repo) {
                        Repository::updateOrCreate(
                            ['url' => $repo['html_url']],
                            [
                                'name' => [
                                    'es' => $repo['name'],
                                    'en' => $repo['name'],
                                ],
                                'description' => [
                                    'es' => $repo['description'],
                                    'en' => $repo['description'],
                                ],
                                'stars' => $repo['stargazers_count'],
                                'technologies' => $repo['language'] ? [$repo['language']] : [],
                                'is_visible' => false, // Default to false so you can choose which one to share
                            ]
                        );
                        $count++;
                    }

                    Notification::make()
                        ->title('Sincronización Completada')
                        ->body("Se han importado/actualizado {$count} repositorios. Todos están ocultos por defecto, activa los que quieras mostrar.")
                        ->success()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }
}
