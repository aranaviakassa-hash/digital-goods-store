<?php

namespace App\Filament\Resources\SecurityReviews\Pages;

use App\Filament\Resources\SecurityReviews\SecurityReviewResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSecurityReviews extends ListRecords
{
    protected static string $resource = SecurityReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
