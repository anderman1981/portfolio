<?php

namespace App\Livewire;

use App\Models\JobPortal;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class JobPortals extends Component
{
    public $searchPortal = '';
    public $selectedCategory = '';

    public function render()
    {
        $portals = JobPortal::query()
            ->when($this->searchPortal, fn($q) => $q->where('name', 'like', "%{$this->searchPortal}%")
                ->orWhere('description', 'like', "%{$this->searchPortal}%"))
            ->when($this->selectedCategory, fn($q) => $q->where('category', $this->selectedCategory))
            ->orderBy('featured', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get();

        $categories = JobPortal::distinct()->pluck('category')->sort();
        $featured = JobPortal::featured()->orderBy('sort_order')->get();

        return view('livewire.job-portals', compact('portals', 'categories', 'featured'));
    }

    public function getCategoryColor($category)
    {
        return match($category) {
            'Remote' => 'purple',
            'Freelance' => 'green',
            'Tech' => 'red',
            'Creative' => 'pink',
            'Writing' => 'yellow',
            'General' => 'blue',
            'Services' => 'orange',
            'Tools' => 'indigo',
            default => 'gray',
        };
    }
}
