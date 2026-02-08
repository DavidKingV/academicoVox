<?php

namespace App\Filament\Resources\SkillTypes;

use App\Filament\Resources\SkillTypes\Pages\ManageSkillTypes;
use App\Models\Skills\SkillType;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkillTypeResource extends Resource
{
    protected static ?string $model = SkillType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Academic');
    }

    public static function getModelLabel(): string
    {
        return __('Skill Type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Skill Types');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('shortname')
                    ->required()
                    ->minLength(1)
                    ->maxLength(8)
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->required()
                    ->minLength(1)
                    ->maxLength(90)
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shortname')
                    ->searchable()
                    ->sortable(),
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
            'index' => ManageSkillTypes::route('/'),
        ];
    }
}
