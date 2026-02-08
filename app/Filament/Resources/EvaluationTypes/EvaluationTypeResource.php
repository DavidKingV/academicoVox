<?php

namespace App\Filament\Resources\EvaluationTypes;

use App\Filament\Resources\EvaluationTypes\Pages\ManageEvaluationTypes;
use App\Models\EvaluationType;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EvaluationTypeResource extends Resource
{
    protected static ?string $model = EvaluationType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Academic');
    }

    public static function getModelLabel(): string
    {
        return __('Evaluation Type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Evaluation Types');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->minLength(1)
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Select::make('gradeTypes')
                    ->relationship('gradeTypes', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Select::make('skills')
                    ->relationship('skills', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => ManageEvaluationTypes::route('/'),
        ];
    }
}
