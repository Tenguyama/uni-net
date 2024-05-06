<?php

namespace App\Filament\Resources\GroupResource\Pages;

use App\Filament\Resources\GroupResource;
use App\Models\FakultDescription;
use App\Models\GroupDescription;
use App\Models\Language;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGroup extends EditRecord
{
    protected static string $resource = GroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $languages = $data['languages'];
        unset($data['languages']);
        $record->update($data);
        foreach ($languages as $langId => $language) {
            $groupDescription = GroupDescription::query()
                ->where('group_id','=',$record->id)
                ->where('language_id' ,'=', $langId)
                ->first();
            if($groupDescription){
                $groupDescription->update($language);
            }
        }
        return $record;
    }

    public function fillFormWithDataAndCallHooks(Model $record, array $extraData = []): void
    {
        $groupDescriptions = $record->groupDescription;
        $languages = Language::all();
        $formData = array();
        $formData['fakult_id'] = $record->fakult_id;
        foreach ($groupDescriptions as $groupDescription) {
            foreach ($languages as $language) {
                if ($groupDescription->language_id == $language->id) {
                    $formData['languages'][$language->id]['name'] = $groupDescription['name'];
                }
            }
        }
        $this->form->fill($formData);
    }
}
