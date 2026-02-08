<?php

namespace App\Filament\Resources\Enrollments;

use App\Filament\Resources\Enrollments\Pages\EditEnrollment;
use App\Filament\Resources\Enrollments\Pages\ListEnrollments;
use App\Models\Enrollment;
use App\Models\Period;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('Academic');
    }

    public static function getModelLabel(): string
    {
        return __('Enrollment');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Enrollments');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'name')
                    ->required()
                    ->preload()
                    ->searchable(),
                TextInput::make('total_price')
                    ->label(__('Price'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix(config('academico.currency_position') === 'before' ? config('academico.currency_symbol') : null)
                    ->suffix(config('academico.currency_position') === 'after' ? config('academico.currency_symbol') : null),
                Select::make('status_id')
                    ->relationship('enrollmentStatus', 'name')
                    ->required()
                    ->preload(),
                Select::make('scholarships')
                    ->relationship('scholarships', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $defaultPeriod = Period::get_default_period();

        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('student.user.lastname')
                    ->label(__('Last name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student.user.firstname')
                    ->label(__('First name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.name')
                    ->label(__('Course'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.period.name')
                    ->label(__('Period'))
                    ->sortable(),
                TextColumn::make('enrollmentStatus.name')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Paid' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('total_price')
                    ->label(__('Price'))
                    ->money(config('academico.currency_code', 'USD'))
                    ->sortable(),
                TextColumn::make('student.user.email')
                    ->label(__('Email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('scholarships.name')
                    ->label(__('Scholarships'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('period')
                    ->query(function ($query, array $data) {
                        if ($data['value']) {
                            $query->whereHas('course', fn ($q) => $q->where('period_id', $data['value']));
                        }
                    })
                    ->options(fn () => Period::pluck('name', 'id'))
                    ->default($defaultPeriod?->id)
                    ->label(__('Period')),
                SelectFilter::make('status_id')
                    ->relationship('enrollmentStatus', 'name')
                    ->label(__('Status'))
                    ->multiple()
                    ->preload(),
            ])
            ->defaultSort('id', 'desc')
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
            'index' => ListEnrollments::route('/'),
            'edit' => EditEnrollment::route('/{record}/edit'),
        ];
    }
}
