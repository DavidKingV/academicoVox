<?php

namespace App\Filament\Resources\Students\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label(__('Course'))
                    ->searchable(),
                TextColumn::make('course.period.name')
                    ->label(__('Period')),
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
                    ->money(config('academico.currency_code', 'USD')),
                TextColumn::make('created_at')
                    ->label(__('Enrolled'))
                    ->date(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }
}
