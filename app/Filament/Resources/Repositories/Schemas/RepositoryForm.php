<?php

namespace App\Filament\Resources\Repositories\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RepositoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('url')
                    ->url()
                    ->required(),
                TextInput::make('category'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TagsInput::make('technologies'),
                TextInput::make('stars')
                    ->numeric()
                    ->default(0),
                TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_visible')
                    ->default(true),
            ]);
    }
}
