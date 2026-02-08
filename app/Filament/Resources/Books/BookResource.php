<?php

namespace App\Filament\Resources\Books;

use App\Filament\Resources\Books\Pages\ManageBooks;
use App\Models\Book;
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

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $cluster = \App\Filament\Clusters\Settings\SettingsCluster::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Finance');
    }

    public static function getModelLabel(): string
    {
        return __('Book');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Books');
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
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix(config('academico.currency_position') === 'before' ? config('academico.currency_symbol') : null)
                    ->suffix(config('academico.currency_position') === 'after' ? config('academico.currency_symbol') : null),
                TextInput::make('product_code')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price_with_currency')
                    ->label(__('Price')),
                TextColumn::make('product_code'),
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
            'index' => ManageBooks::route('/'),
        ];
    }
}
