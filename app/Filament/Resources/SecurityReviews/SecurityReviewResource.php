<?php

namespace App\Filament\Resources\SecurityReviews;

use App\Filament\Resources\SecurityReviews\Pages\CreateSecurityReview;
use App\Filament\Resources\SecurityReviews\Pages\EditSecurityReview;
use App\Filament\Resources\SecurityReviews\Pages\ListSecurityReviews;
use App\Filament\Resources\SecurityReviews\Schemas\SecurityReviewForm;
use App\Filament\Resources\SecurityReviews\Tables\SecurityReviewsTable;
use App\Models\SecurityReview;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SecurityReviewResource extends Resource
{
    protected static ?string $model = SecurityReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Title attribute: status';

    public static function form(Schema $schema): Schema
    {
        return SecurityReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SecurityReviewsTable::configure($table);
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
            'index' => ListSecurityReviews::route('/'),
            'create' => CreateSecurityReview::route('/create'),
            'edit' => EditSecurityReview::route('/{record}/edit'),
        ];
    }
}
