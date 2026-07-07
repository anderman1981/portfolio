<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\JobApplicationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJobApplication extends EditRecord
{
    protected static string $resource = JobApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
