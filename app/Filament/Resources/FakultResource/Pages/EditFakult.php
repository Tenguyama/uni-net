<?php

namespace App\Filament\Resources\FakultResource\Pages;

use App\Filament\Resources\FakultResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFakult extends EditRecord
{
    protected static string $resource = FakultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
