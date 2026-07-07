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
        return view('livewire.portfolio-view', [
            'experiences' => Experience::orderBy('id', 'asc')->get(), // Simple order for now
            'skills' => Skill::all()->groupBy('category'),
            'projects' => Project::all(),
            'repositories' => Repository::where('is_visible', true)->orderBy('sort_order')->get(),
            'education' => Education::all(),
        ])->layout('layouts.app');
    }
}
