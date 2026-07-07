<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company')
                    ->required(),
                TextInput::make('role')
                    ->required(),
                TextInput::make('location'),
                TextInput::make('start_date')
                    ->required(),
                TextInput::make('end_date'),
                Repeater::make('achievements')
                    ->simple(TextInput::make('achievement'))
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_current')
                    ->required(),
            ]);
    }
}
