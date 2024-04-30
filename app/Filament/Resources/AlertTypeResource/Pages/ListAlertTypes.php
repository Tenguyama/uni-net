<?php

namespace App\Filament\Resources\AlertTypeResource\Pages;

use App\Filament\Resources\AlertTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlertTypes extends ListRecords
{
    protected static string $resource = AlertTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
