<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->label('Order')
                    ->options(
                        Order::query()
                            ->orderByDesc('id')
                            ->pluck('order_number', 'id')
                    )
                    ->searchable()
                    ->required(),

                Select::make('product_id')
                    ->label('Product')
                    ->options(
                        Product::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->nullable(),

                TextInput::make('product_name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('product_code')
                    ->maxLength(255),

                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set(
                            'total_price',
                            (float) $state * (float) ($get('unit_price') ?? 0)
                        );
                    }),

                TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->prefix('AZN')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set(
                            'total_price',
                            (float) $state * (float) ($get('quantity') ?? 1)
                        );
                    }),

                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('AZN')
                    ->readOnly(),

                TextInput::make('currency')
                    ->required()
                    ->default('AZN')
                    ->maxLength(3),

                Textarea::make('delivery_data')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}