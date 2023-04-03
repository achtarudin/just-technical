<?php

namespace App\Filament\Resources\AccountResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AccountResource;

class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
