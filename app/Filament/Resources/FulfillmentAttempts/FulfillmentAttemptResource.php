<?php

namespace App\Filament\Resources\FulfillmentAttempts;

use App\Filament\Resources\FulfillmentAttempts\Pages\CreateFulfillmentAttempt;
use App\Filament\Resources\FulfillmentAttempts\Pages\EditFulfillmentAttempt;
use App\Filament\Resources\FulfillmentAttempts\Pages\ListFulfillmentAttempts;
use App\Filament\Resources\FulfillmentAttempts\Schemas\FulfillmentAttemptForm;
use App\Filament\Resources\FulfillmentAttempts\Tables\FulfillmentAttemptsTable;
use App\Models\FulfillmentAttempt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FulfillmentAttemptResource extends Resource
public static function canEdit($record): bool
{
    return false;
}

public static function canDelete($record): bool
{
    return false;
}
{
    protected static ?string $model = FulfillmentAttempt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Title attribute: status';

    public static function form(Schema $schema): Schema
    {
        return FulfillmentAttemptForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FulfillmentAttemptsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFulfillmentAttempts::route('/'),
            'create' => CreateFulfillmentAttempt::route('/create'),
            'edit' => EditFulfillmentAttempt::route('/{record}/edit'),
        ];
    }
}
