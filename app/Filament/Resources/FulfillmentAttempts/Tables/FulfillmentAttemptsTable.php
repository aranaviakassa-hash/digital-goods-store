<?php

namespace App\Filament\Resources\FulfillmentAttempts\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FulfillmentAttemptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supplier')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('supplier_reference')
                    ->label('Supplier Ref')
                    ->searchable(),

                TextColumn::make('fulfilled_at')
                    ->label('Fulfilled At')
                    ->dateTime(),

                TextColumn::make('failed_at')
                    ->label('Failed At')
                    ->dateTime(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}