<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('payment_status')
                    ->required()
                    ->default('unpaid'),
                TextInput::make('fulfillment_status')
                    ->required()
                    ->default('pending'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('currency')
                    ->required()
                    ->default('AZN'),
                TextInput::make('customer_email')
                    ->email()
                    ->required(),
                TextInput::make('customer_name'),
                TextInput::make('payment_provider'),
                TextInput::make('payment_reference'),
                TextInput::make('supplier_reference'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
