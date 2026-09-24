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
                    ->maxLength(255),

                TextInput::make('supplier_product_code')
                    ->maxLength(255),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('AZN'),

                TextInput::make('currency')
                    ->required()
                    ->default('AZN')
                    ->maxLength(3),

                Toggle::make('is_active')
                    ->default(false),

                Toggle::make('resale_verified')
                    ->default(false),

                Toggle::make('bank_approved')
                    ->default(false),

                Textarea::make('description')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}