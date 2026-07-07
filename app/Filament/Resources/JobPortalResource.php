<?php

namespace App\Filament\Resources;

use App\Models\JobPortal;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput as NumberInput;

class JobPortalResource extends Resource
{
    protected static ?string $model = JobPortal::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static ?string $navigationLabel = 'Job Portals';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            // Basic Info
            TextInput::make('name')->required()->label('Portal Name'),
            Textarea::make('description')->required()->label('Description'),
            TextInput::make('url')->required()->url()->label('URL'),
            Select::make('category')->required()->options([
                'General' => 'General',
                'Remote' => 'Remote',
                'Freelance' => 'Freelance',
                'Tech' => 'Tech',
                'Creative' => 'Creative',
                'Writing' => 'Writing',
                'Services' => 'Services',
                'Tools' => 'Tools',
            ]),
            Textarea::make('specialty')->label('Specialty/Focus'),
            Select::make('icon_color')->options([
                'blue' => 'Blue',
                'purple' => 'Purple',
                'green' => 'Green',
                'red' => 'Red',
                'pink' => 'Pink',
                'yellow' => 'Yellow',
                'gray' => 'Gray',
                'orange' => 'Orange',
                'indigo' => 'Indigo',
            ]),

            // Display Options
            Toggle::make('featured')->label('Featured Portal'),
            NumberInput::make('sort_order')->numeric()->default(0),
            Toggle::make('active')->label('Active for Automation')->default(true),

            // Authentication Section
            Select::make('auth_type')
                ->options([
                    'none' => '❌ No Authentication',
                    'email' => '📧 Email & Password',
                    'google' => '🔵 Google Login',
                    'github' => '⚫ GitHub Login',
                    'linkedin' => '🔗 LinkedIn Login',
                    'api_key' => '🔑 API Key',
                    'two_factor' => '🔐 Email + 2FA',
                    'other' => '❓ Other',
                ])
                ->label('Authentication Type')
                ->reactive(),

            TextInput::make('email')
                ->label('Email / Username')
                ->email('email')
                ->nullable()
                ->visible(fn($get) => in_array($get('auth_type'), ['email', 'google', 'two_factor', 'other'])),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->nullable()
                ->revealable()
                ->visible(fn($get) => in_array($get('auth_type'), ['email', 'two_factor', 'other']))
                ->helperText('🔒 Automatically encrypted'),

            TextInput::make('api_key')
                ->label('API Key')
                ->password()
                ->nullable()
                ->revealable()
                ->visible(fn($get) => in_array($get('auth_type'), ['api_key', 'other']))
                ->helperText('🔒 Automatically encrypted'),

            Textarea::make('additional_data')
                ->label('Additional Info (JSON)')
                ->nullable()
                ->rows(3)
                ->helperText('For 2FA codes, security questions, or extra fields. Format: {"field": "value"}'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('category')->searchable()->badge(),
                TextColumn::make('auth_type')
                    ->label('Auth Type')
                    ->badge()
                    ->formatStateUsing(fn($state) => match($state) {
                        'none' => '❌ None',
                        'email' => '📧 Email/Pass',
                        'google' => '🔵 Google',
                        'github' => '⚫ GitHub',
                        'linkedin' => '🔗 LinkedIn',
                        'api_key' => '🔑 API',
                        'two_factor' => '🔐 2FA',
                        default => '❓ Other',
                    })
                    ->color(fn($state) => match($state) {
                        'none' => 'gray',
                        'email' => 'warning',
                        'google' => 'info',
                        'github' => 'secondary',
                        'linkedin' => 'info',
                        'api_key' => 'success',
                        'two_factor' => 'danger',
                        default => 'gray',
                    }),
                BadgeColumn::make('active')
                    ->label('Active')
                    ->colors(['success' => true, 'danger' => false]),
                TextColumn::make('email')->searchable()->limit(20),
                TextColumn::make('url')->copyable(),
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
            'index' => Pages\ListJobPortals::route('/'),
            'create' => Pages\CreateJobPortal::route('/create'),
            'edit' => Pages\EditJobPortal::route('/{record}/edit'),
        ];
    }
}
