<?php

namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupDescription;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGroup extends CreateRecord
{
    protected static string $resource = GroupResource::class;

    public function create(bool $another = false): void
    {
        $data = $this->form->getState();
        $languages = $data['languages'];
        unset($data['languages']);
        $group = Group::query()->create($data);

        foreach ($languages as $langId => $language) {
            GroupDescription::query()->create([
                'group_id' => $group->id,
                'language_id' => $langId,
                'name' => $language['name'],
            ]);
        }

        $this->redirect('/admin/groups');
    }
}
