<?php

namespace App\Filament\Resources\FulfillmentAttempts\Tables;

use App\Models\FulfillmentAttempt;
use App\Services\ManualFulfillmentResolutionService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
            ->recordActions([
                Action::make('resolveFulfilled')
                    ->label('Confirm fulfilled')
                    ->visible(
                        fn (FulfillmentAttempt $record): bool =>
                            $record->status === 'unknown'
                    )
                    ->schema([
                        TextInput::make('supplier_reference')
                            ->label('Supplier reference')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('notes')
                            ->label('Resolution notes')
                            ->required()
                            ->rows(4)
                            ->maxLength(2000),
                    ])
                    ->requiresConfirmation()
                    ->action(function (
                        FulfillmentAttempt $record,
                        array $data
                    ): void {
                        app(ManualFulfillmentResolutionService::class)
                            ->resolveAsFulfilled(
                                $record,
                                $data['supplier_reference'],
                                $data['notes']
                            );
                    }),

                Action::make('resolveFailed')
                    ->label('Confirm failed')
                    ->color('danger')
                    ->visible(
                        fn (FulfillmentAttempt $record): bool =>
                            $record->status === 'unknown'
                    )
                    ->schema([
                        Textarea::make('notes')
                            ->label('Failure evidence / notes')
                            ->required()
                            ->rows(4)
                            ->maxLength(2000),
                    ])
                    ->requiresConfirmation()
                    ->action(function (
                        FulfillmentAttempt $record,
                        array $data
                    ): void {
                        app(ManualFulfillmentResolutionService::class)
                            ->resolveAsFailed(
                                $record,
                                $data['notes']
                            );
                    }),
            ])
            ->toolbarActions([]);
    }
}
