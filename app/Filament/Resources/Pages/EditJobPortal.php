<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\JobPortalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJobPortal extends EditRecord
{
    protected static string $resource = JobPortalResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
