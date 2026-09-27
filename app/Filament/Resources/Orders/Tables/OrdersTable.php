<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer_email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('sold_products')
                    ->label('Product')
                    ->state(
                        fn ($record) =>
                            $record->items
                                ->map(
                                    fn ($item) =>
                                        $item->product_name .
                                        ' x' .
                                        $item->quantity
                                )
                                ->join(', ')
                    ),

                TextColumn::make('unit_prices')
                    ->label('Price')
                    ->state(
                        fn ($record) =>
                            $record->items
                                ->map(
                                    fn ($item) =>
                                        number_format(
                                            (float) $item->unit_price,
                                            2
                                        ) .
                                        ' ' .
                                        $item->currency
                                )
                                ->join(', ')
                    ),

                TextColumn::make('total')
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

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge(),

                TextColumn::make('fulfillment_status')
                    ->label('Fulfillment')
                    ->badge(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort(
                'created_at',
                'desc'
            );
    }
}