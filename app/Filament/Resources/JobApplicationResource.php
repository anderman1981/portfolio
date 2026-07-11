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
use Filament\Actions\Action;
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
            TextInput::make('fit_score')->numeric()->label('Fit Score (/100)')->placeholder('77'),
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
            TextInput::make('cv_path')->label('CV file name')->placeholder('main_leadtech')
                ->helperText('Name of the .md in cv/ (without extension)'),
            TextInput::make('cover_path')->label('Cover file name')->placeholder('cover_leadtech_ai_native_developer')
                ->helperText('Name of the .md in cover_letters/ (without extension)'),
            Textarea::make('evaluation_notes')->label('Fit Evaluation')->rows(4)->columnSpanFull(),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company')->searchable()->sortable(),
                TextColumn::make('position')->searchable()->limit(35),
                TextColumn::make('fit_score')
                    ->label('Fit')
                    ->badge()
                    ->suffix('/100')
                    ->color(fn ($state) => $state >= 75 ? 'success' : ($state >= 60 ? 'warning' : 'gray'))
                    ->placeholder('—'),
                BadgeColumn::make('status'),
                TextColumn::make('application_date')->date()->sortable(),
            ])
            ->recordActions([
                Action::make('cv')
                    ->label('CV')
                    ->icon(Heroicon::OutlinedDocumentText)
                    ->color('info')
                    ->url(fn (Application $r) => $r->cvUrl(), shouldOpenInNewTab: true)
                    ->visible(fn (Application $r) => (bool) $r->cv_path),
                Action::make('cover')
                    ->label('Cover')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->color('warning')
                    ->url(fn (Application $r) => $r->coverUrl(), shouldOpenInNewTab: true)
                    ->visible(fn (Application $r) => (bool) $r->cover_path),
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
