<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\JobListingResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class ListJobListings extends ListRecords
{
    protected static string $resource = JobListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('scan')
                ->label('Scan Now')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function () {
                    Artisan::call('jobs:scan');
                    Notification::make()
                        ->title('Job scan complete')
                        ->body('Fetched latest listings from all sources.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
