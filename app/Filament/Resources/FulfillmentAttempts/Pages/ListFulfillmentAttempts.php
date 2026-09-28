<?php

namespace App\Filament\Resources\FulfillmentAttempts\Pages;

use App\Filament\Resources\FulfillmentAttempts\FulfillmentAttemptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFulfillmentAttempts extends ListRecords
{
    protected static string $resource = FulfillmentAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
