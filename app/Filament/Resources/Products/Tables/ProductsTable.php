<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('supplier')
                    ->placeholder('Not assigned')
                    ->searchable(),

                TextColumn::make('supplier_product_code')
                    ->label('Supplier Code')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(
                        function ($state, $record): string {
                            if ($state === null) {
                                return 'Not set';
                            }

                            return number_format(
                                (float) $state,
                                2
                            )
                            . ' '
                            . $record->currency;
                        }
                    )
                    ->sortable(),

                IconColumn::make('catalog_visible')
                    ->label('Catalog')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                IconColumn::make('resale_verified')
                    ->label('Resale')
                    ->boolean(),

                IconColumn::make('bank_approved')
                    ->label('Bank')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(
                        isToggledHiddenByDefault: true
                    ),
            ])

            ->defaultSort(
                'updated_at',
                'desc'
            )

            ->recordActions([
                EditAction::make(),
            ]);
    }
}