<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('category')
                    ->required(),
                Slider::make('proficiency')
                    ->required()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(100),
                TextInput::make('icon'),
            ]);
    }
}
