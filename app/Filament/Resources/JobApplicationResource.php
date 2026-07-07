<?php

namespace App\Filament\Resources;

use App\Models\Application;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class JobApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $navigationLabel = 'Job Applications';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('company')->required(),
            TextInput::make('position')->required(),
            DatePicker::make('application_date')->required(),
            Select::make('evaluation_id')->relationship('evaluation', 'company')->preload(),
            TextInput::make('score')->placeholder('4.8/5'),
            Select::make('status')->options([
                'Postulado' => 'Postulado',
                'En Revisión' => 'En Revisión',
                'Entrevista' => 'Entrevista',
                'Prueba Técnica' => 'Prueba Técnica',
                'Oferta' => 'Oferta',
                'Rechazado' => 'Rechazado',
                'Aceptado' => 'Aceptado',
            ])->required(),
            TextInput::make('link')->url()->label('Job URL'),
            Textarea::make('notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company')->searchable()->sortable(),
                TextColumn::make('position')->searchable(),
                TextColumn::make('score')->badge(),
                BadgeColumn::make('status'),
                TextColumn::make('application_date')->date()->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobApplications::route('/'),
            'create' => Pages\CreateJobApplication::route('/create'),
            'edit' => Pages\EditJobApplication::route('/{record}/edit'),
        ];
    }
}
