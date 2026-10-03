<?php

namespace App\Filament\Resources\OrderItems\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(),

                TextColumn::make('product_code')
                    ->label('Product Code')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->sortable(),

                TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->formatStateUsing(
                        fn ($state, $record) =>
                            number_format(
                                (float) $state,
                                2
                            ) .
                            ' ' .
                            $record->currency
                    )
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->formatStateUsing(
                        fn ($state, $record) =>
                            number_format(
                                (float) $state,
                                2
                            ) .
                            ' ' .
                            $record->currency
                    )
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}