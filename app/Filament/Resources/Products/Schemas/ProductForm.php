<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('category')
                    ->required()
                    ->maxLength(255),

                TextInput::make('supplier')
                    ->maxLength(255)
                    ->helperText(
                        'Leave blank until a real supplier is confirmed.'
                    ),

                TextInput::make('supplier_product_code')
                    ->maxLength(255)
                    ->helperText(
                        'Use only the actual supplier product/SKU reference.'
                    ),

                TextInput::make('price')
                    ->numeric()
                    ->minValue(0)
                    ->prefix('AZN')
                    ->helperText(
                        'May be left blank for review-only catalogue products. A live sellable product must have a price.'
                    ),

                TextInput::make('currency')
                    ->required()
                    ->default('AZN')
                    ->maxLength(3),

                Toggle::make('catalog_visible')
                    ->label('Catalog visible')
                    ->default(false)
                    ->helperText(
                        'Shows the product publicly for catalogue/bank review. This does not make it sellable.'
                    ),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(false)
                    ->helperText(
                        'Operational product status.'
                    ),

                Toggle::make('resale_verified')
                    ->label('Resale verified')
                    ->default(false)
                    ->helperText(
                        'Enable only after the commercial/resale basis has been verified.'
                    ),

                Toggle::make('bank_approved')
                    ->label('Bank approved')
                    ->default(false)
                    ->helperText(
                        'Enable only after merchant/acquiring approval permits live sale.'
                    ),

                Textarea::make('description')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}