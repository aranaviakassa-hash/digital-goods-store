<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
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
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->sortable(),

                TextColumn::make('fulfillment_status')
                    ->label('Fulfillment')
                    ->badge()
                    ->sortable(),

                TextColumn::make('total')
                    ->formatStateUsing(fn ($state, $record) => number_format((float) $state, 2) . ' ' . $record->currency)
                    ->sortable(),

                TextColumn::make('payment_provider')
                    ->label('Payment Provider')
                    ->toggleable(),

                TextColumn::make('payment_reference')
                    ->label('Payment Ref')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('supplier_reference')
                    ->label('Supplier Ref')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}