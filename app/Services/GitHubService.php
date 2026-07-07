<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected $token;

    protected $username;

    public function __construct()
    {
        $this->token = config('services.github.token');
        $this->username = config('services.github.username');
    }

    public function getUserRepositories()
    {
        if (! $this->username) {
            return [];
        }

        $response = Http::withHeaders([
            'Authorization' => $this->token ? "Bearer {$this->token}" : null,
            'Accept' => 'application/vnd.github.v3+json',
        ])->get("https://api.github.com/users/{$this->username}/repos", [
            'sort' => 'updated',
            'per_page' => 100,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }
}
