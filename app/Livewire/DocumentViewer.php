<?php

namespace App\Livewire;

use App\Models\Document;
use Livewire\Attributes\Url;
use Livewire\Component;

class DocumentViewer extends Component
{
    #[Url]
    public int $evaluation = 0;

    #[Url]
    public string $type = 'cover';

    public ?Document $document = null;
    public string $title = '';

    protected $layout = 'components.layouts.app';

    public function mount()
    {
        if ($this->evaluation > 0) {
            $this->document = Document::where('evaluation_id', $this->evaluation)
                ->where('type', $this->type)
                ->firstOrFail();

            $this->title = match($this->type) {
                'cover' => 'Cover Letter',
                'summary' => 'Executive Summary',
                'report' => 'Full Evaluation',
                default => 'Document',
            };
        }
    }

    public function render()
    {
        return view('livewire.document-viewer');
    }
}
