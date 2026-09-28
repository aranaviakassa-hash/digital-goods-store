<?php

namespace App\Filament\Resources\FulfillmentAttempts\Pages;

use App\Filament\Resources\FulfillmentAttempts\FulfillmentAttemptResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFulfillmentAttempt extends EditRecord
{
    protected static string $resource = FulfillmentAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
