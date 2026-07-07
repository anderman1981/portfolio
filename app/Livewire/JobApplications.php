<?php

namespace App\Livewire;

use App\Models\Application;
use Livewire\Component;
use Livewire\WithPagination;

class JobApplications extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $sortBy = 'application_date';

    protected $layout = 'components.layouts.app';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Application::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('company', 'like', "%{$this->search}%")
                  ->orWhere('position', 'like', "%{$this->search}%")
                  ->orWhere('notes', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $applications = $query
            ->orderBy($this->sortBy, 'desc')
            ->paginate(10);

        $stats = [
            'total' => Application::count(),
            'by_status' => Application::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status'),
            'avg_score' => Application::whereNotNull('score')
                ->selectRaw('AVG(CAST(REPLACE(score, "/5", "") AS DECIMAL(3,1))) as avg')
                ->value('avg'),
        ];

        return view('livewire.job-applications', compact('applications', 'stats'));
    }
}
