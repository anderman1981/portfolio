<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\JobPortalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobPortals extends ListRecords
{
    protected static string $resource = JobPortalResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
