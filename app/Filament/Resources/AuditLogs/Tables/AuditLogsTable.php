<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('auditable_type')
                    ->label('Type')
                    ->toggleable(),

                TextColumn::make('auditable_id')
                    ->label('Record ID')
                    ->sortable(),

                TextColumn::make('user_id')
                    ->label('User ID')
                    ->sortable(),

                TextColumn::make('ip_address')
                    ->label('IP'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}