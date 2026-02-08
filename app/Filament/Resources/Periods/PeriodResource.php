<?php

namespace App\Filament\Resources\Periods;

use App\Filament\Resources\Periods\Pages\ListPeriods;
use App\Models\Period;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PeriodResource extends Resource
{
    protected static ?string $model = Period::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    public static function getNavigationGroup(): ?string
    {
        return __('Academic');
    }

    public static function getModelLabel(): string
    {
        return __('Period');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Periods');
    }

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('year_id')
                    ->relationship('year', 'name')
                    ->required()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->minLength(1)
                            ->maxLength(10),
                    ]),
                TextInput::make('name')
                    ->required()
                    ->minLength(1)
                    ->maxLength(40)
                    ->unique(ignoreRecord: true),
                DatePicker::make('start')
                    ->required(),
                DatePicker::make('end')
                    ->required(),
                Toggle::make('archived')
                    ->label(__('Archived')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('year.name')
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start')
                    ->date()
                    ->sortable(),
                TextColumn::make('end')
                    ->date()
                    ->sortable(),
                IconColumn::make('archived')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('year_id')
                    ->relationship('year', 'name')
                    ->label(__('Year'))
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => ListPeriods::route('/'),
        ];
    }
}
