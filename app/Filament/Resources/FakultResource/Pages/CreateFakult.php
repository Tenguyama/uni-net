<?php

namespace App\Filament\Resources\FakultResource\Pages;

use App\Filament\Resources\FakultResource;
use App\Models\Fakult;
use App\Models\FakultDescription;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFakult extends CreateRecord
{
    protected static string $resource = FakultResource::class;

    public function create(bool $another = false): void
    {
        $data = $this->form->getState();
        $languages = $data['languages'];
        unset($data['languages']);
        $fakult = Fakult::query()->create($data);

        foreach ($languages as $langId => $language) {
            FakultDescription::query()->create([
                'fakult_id' => $fakult->id,
                'language_id' => $langId,
                'name' => $language['name'],
            ]);
        }

        $this->redirect('/admin/fakults');
    }
}
