<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric()
                    ->nullable(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('pending'),

                Select::make('payment_status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->required()
                    ->default('unpaid'),

                Select::make('fulfillment_status')
                    ->options([
                        'pending' => 'Pending',
                        'security_review' => 'Security Review',
                        'processing' => 'Processing',
                        'fulfilled' => 'Fulfilled',
                        'failed' => 'Failed',
                    ])
                    ->required()
                    ->default('pending'),

                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('AZN')
                    ->default(0),

                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('AZN')
                    ->default(0),

                TextInput::make('currency')
                    ->required()
                    ->default('AZN')
                    ->maxLength(3),

                TextInput::make('customer_email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('customer_name')
                    ->maxLength(255),

                TextInput::make('payment_provider')
                    ->maxLength(255),

                TextInput::make('payment_reference')
                    ->maxLength(255),

                TextInput::make('supplier_reference')
                    ->maxLength(255),

                Textarea::make('notes')
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }
}