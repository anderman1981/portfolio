<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\JobApplicationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobApplication extends CreateRecord
{
    protected static string $resource = JobApplicationResource::class;
}
