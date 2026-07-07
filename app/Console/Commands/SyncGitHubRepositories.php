<?php

namespace App\Console\Commands;

use App\Models\Repository;
use App\Services\GitHubService;
use Illuminate\Console\Command;

class SyncGitHubRepositories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-repos {--force : Sobrescribir descripciones existentes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza los repositorios de GitHub con la base de datos local';

    /**
     * Execute the console command.
     */
    public function handle(GitHubService $gitHubService)
    {
        $this->info('Iniciando sincronización de repositorios desde GitHub...');

        $repos = $gitHubService->getUserRepositories();

        if (empty($repos)) {
            $this->error('No se pudieron obtener repositorios de GitHub. Verifica el username y el token.');

            return 1;
        }

        $bar = $this->output->createProgressBar(count($repos));
        $bar->start();

        foreach ($repos as $repoData) {
            $repository = Repository::updateOrCreate(
                ['url' => $repoData['html_url']],
                [
                    'stars' => $repoData['stargazers_count'],
                    'technologies' => array_merge([$repoData['language']], $repoData['topics'] ?? []),
                    'is_visible' => $repoData['visibility'] === 'public',
                ]
            );

            // Solo actualizar nombre y descripción si son nuevos o si se usa --force
            if ($repository->wasRecentlyCreated || $this->option('force')) {
                $repository->setTranslation('name', 'es', $repoData['name']);
                $repository->setTranslation('name', 'en', $repoData['name']);

                $description = $repoData['description'] ?? 'No description provided.';
                $repository->setTranslation('description', 'es', $description);
                $repository->setTranslation('description', 'en', $description);
            }

            $repository->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Sincronización completada con éxito.');

        return 0;
    }
}
