<?php

namespace App\Livewire;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Repository;
use App\Models\Skill;
use Livewire\Component;

class PortfolioView extends Component
{
    public function render()
    {
        // Reverse-chronological: newest role first (nulls last).
        $experiences = Experience::orderByRaw('start_date IS NULL, start_date DESC')->get();

        // Order projects to follow the recency of the company they belong to.
        // Projects reference their company in the title, e.g. "... (INGACOV)".
        $companyRank = [];
        foreach ($experiences->values() as $i => $exp) {
            $token = strtolower(explode(' ', trim($exp->company))[0]);
            if ($token !== '' && !isset($companyRank[$token])) {
                $companyRank[$token] = $i;
            }
        }

        $projects = Project::all()->sortBy(function ($project) use ($companyRank) {
            $title = strtolower($project->title);
            foreach ($companyRank as $token => $rank) {
                if (str_contains($title, $token)) {
                    return $rank;
                }
            }
            return 999; // unmatched projects go last
        })->values();

        return view('livewire.portfolio-view', [
            'experiences' => $experiences,
            'skills' => Skill::all()->groupBy('category'),
            'projects' => $projects,
            'repositories' => Repository::where('is_visible', true)->orderBy('sort_order')->get(),
            'education' => Education::all(),
        ])->layout('layouts.app');
    }
}
