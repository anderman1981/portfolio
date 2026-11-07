<?php

namespace App\Filament\Resources;

use App\Models\JobListing;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;

class JobListingResource extends Resource
{
    protected static ?string $model = JobListing::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'Job Feed';

    public static function getNavigationBadge(): ?string
    {
        return (string) JobListing::active()->where('is_applied', false)->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('title')->required(),
            TextInput::make('company'),
            TextInput::make('url')->url()->required()->columnSpanFull(),
            TextInput::make('salary'),
            TextInput::make('location'),
            TextInput::make('source')->disabled(),
            Toggle::make('is_favorite')->label('Favorite'),
            Toggle::make('is_applied')->label('Applied'),
            Toggle::make('is_dismissed')->label('Dismissed'),
            Textarea::make('description')->disabled()->rows(12)->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('score', 'desc')
            ->columns([
                IconColumn::make('is_favorite')
                    ->label('★')
                    ->boolean()
                    ->trueIcon(Heroicon::Star)
                    ->falseIcon(Heroicon::OutlinedStar)
                    ->action(function (JobListing $record) {
                        $record->update(['is_favorite' => !$record->is_favorite]);
                    }),
                TextColumn::make('score')
                    ->label('Score')
                    ->sortable()
                    ->badge()
                    ->placeholder('—')
                    ->color(fn (?int $state) => match (true) {
                        $state === null => 'gray',
                        $state >= config('job_search.bands.high') => 'success',
                        $state >= config('job_search.bands.medium') => 'warning',
                        default => 'gray',
                    })
                    ->tooltip(fn (JobListing $r) => $r->score_breakdown
                        ? 'Stack ' . $r->score_breakdown['stack'] . ' (' . implode(', ', $r->score_breakdown['stack_matched'] ?? []) . ')'
                            . ' · Role ' . $r->score_breakdown['role']
                            . ' · Seniority ' . $r->score_breakdown['seniority'] . ' (' . $r->score_breakdown['seniority_level'] . ')'
                            . ' · Region ' . $r->score_breakdown['region']
                            . ' · Salary ' . $r->score_breakdown['salary']
                            . ($r->score_breakdown['penalty'] ? ' · Penalty ' . $r->score_breakdown['penalty'] . ' (' . implode(', ', $r->score_breakdown['penalty_terms']) . ')' : '')
                        : null),
                IconColumn::make('eligible_region')
                    ->label('LATAM')
                    ->boolean()
                    ->trueIcon(Heroicon::CheckCircle)
                    ->falseIcon(Heroicon::XCircle)
                    ->tooltip(fn (JobListing $r) => match ($r->eligible_region) {
                        true => 'Open to Colombia / LATAM / worldwide',
                        false => 'Restricted to another region',
                        default => 'Region not stated',
                    }),
                TextColumn::make('title')->searchable()->wrap()->limit(60),
                TextColumn::make('company')->searchable()->sortable(),
                TextColumn::make('salary')->badge()->color('success')->placeholder('—'),
                TextColumn::make('source')->badge(),
                TextColumn::make('location')->limit(20)->placeholder('—'),
                TextColumn::make('published_at')->date()->sortable(),
                IconColumn::make('is_applied')->label('Applied')->boolean(),
                IconColumn::make('apply_queued')->label('Queued')->boolean()
                    ->trueIcon(Heroicon::QueueList)->falseIcon(Heroicon::Minus),
            ])
            ->filters([
                SelectFilter::make('source')->options([
                    'Remotive' => 'Remotive',
                    'RemoteOK' => 'RemoteOK',
                    'WeWorkRemotely' => 'WeWorkRemotely',
                    'Jobicy' => 'Jobicy',
                    'Himalayas' => 'Himalayas',
                ]),
                SelectFilter::make('band')
                    ->label('Score band')
                    ->options(['high' => 'High (70+)', 'medium' => 'Medium (50-69)', 'low' => 'Low (<50)'])
                    ->query(function (Builder $query, array $data) {
                        $high = config('job_search.bands.high');
                        $medium = config('job_search.bands.medium');
                        return match ($data['value'] ?? null) {
                            'high' => $query->where('score', '>=', $high),
                            'medium' => $query->where('score', '>=', $medium)->where('score', '<', $high),
                            'low' => $query->where(fn ($q) => $q->where('score', '<', $medium)->orWhereNull('score')),
                            default => $query,
                        };
                    }),
                TernaryFilter::make('eligible_region')
                    ->label('Region')
                    ->placeholder('All')
                    ->trueLabel('Eligible (LATAM / worldwide)')
                    ->falseLabel('Restricted')
                    ->queries(
                        true: fn (Builder $q) => $q->where('eligible_region', true),
                        false: fn (Builder $q) => $q->where('eligible_region', false),
                        blank: fn (Builder $q) => $q,
                    ),
                Filter::make('hide_duplicates')
                    ->label('Hide cross-source duplicates')
                    ->query(fn (Builder $q) => $q->whereNull('duplicate_of'))
                    ->default(),
                TernaryFilter::make('is_favorite')->label('Favorites'),
                TernaryFilter::make('is_applied')->label('Applied'),
                TernaryFilter::make('apply_queued')->label('Queued for /apply'),
                TernaryFilter::make('is_dismissed')->label('Dismissed')->default(false),
            ])
            ->recordActions([
                Action::make('open')
                    ->label('Open')
                    ->icon(Heroicon::ArrowTopRightOnSquare)
                    ->url(fn (JobListing $record) => $record->url, shouldOpenInNewTab: true),
                // A. Apply with AI (Gemini) — generates evaluation + CV + cover, saves to Job Applications
                Action::make('applyAi')
                    ->label('Apply with AI')
                    ->icon(Heroicon::Sparkles)
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Generate application with AI')
                    ->modalDescription('Gemini will evaluate the fit and draft a tailored CV and cover letter, then save them to Job Applications.')
                    ->action(function (JobListing $record) {
                        try {
                            $app = app(\App\Services\GeminiApplyService::class)->apply($record);
                            $record->update(['is_applied' => true, 'apply_queued' => false]);
                            \Filament\Notifications\Notification::make()
                                ->title('Application generated')
                                ->body("Fit {$app->fit_score}/100 · CV + cover saved. See Job Applications.")
                                ->success()->send();
                        } catch (\Throwable $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('AI apply failed')
                                ->body($e->getMessage())
                                ->danger()->persistent()->send();
                        }
                    }),
                // B. Queue for /apply — flag it, then run /apply in the terminal with Claude Code
                Action::make('queueApply')
                    ->label(fn (JobListing $r) => $r->apply_queued ? 'Queued ✓' : 'Queue for /apply')
                    ->icon(Heroicon::QueueList)
                    ->color('gray')
                    ->action(fn (JobListing $record) => $record->update(['apply_queued' => !$record->apply_queued])),
                Action::make('applied')
                    ->label('Mark Applied')
                    ->icon(Heroicon::CheckCircle)
                    ->color('success')
                    ->action(fn (JobListing $record) => $record->update(['is_applied' => true])),
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
            'index' => Pages\ListJobListings::route('/'),
            'edit' => Pages\EditJobListing::route('/{record}/edit'),
        ];
    }
}
