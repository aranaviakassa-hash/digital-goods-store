<?php

namespace App\Filament\Resources\SecurityReviews\Pages;

use App\Filament\Resources\SecurityReviews\SecurityReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSecurityReview extends EditRecord
{
    protected static string $resource = SecurityReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
