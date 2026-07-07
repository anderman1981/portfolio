<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'evaluation_id',
        'type',
        'content',
        'external_path',
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function toHtml(): string
    {
        return $this->markdownToHtml($this->content);
    }

    private function markdownToHtml(string $md): string
    {
        $html = htmlspecialchars($md, ENT_QUOTES, 'UTF-8');

        // Headers
        $html = preg_replace('/^### (.*?)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.*?)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^# (.*?)$/m', '<h1>$1</h1>', $html);

        // Bold and italic
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);
        $html = preg_replace('/__([^_]*?)__/', '<strong>$1</strong>', $html);
        $html = preg_replace('/_([^_]*?)_/', '<em>$1</em>', $html);

        // Links
        $html = preg_replace('/\[([^\]]+?)\]\(([^\)]+?)\)/', '<a href="$2" target="_blank">$1</a>', $html);

        // Inline code
        $html = preg_replace('/`([^`]+?)`/', '<code>$1</code>', $html);

        // Line breaks to paragraphs
        $html = trim($html);
        $html = preg_replace('/\n\n+/', '</p><p>', $html);
        $html = '<p>' . $html . '</p>';

        return $html;
    }
}
