<?php

namespace App\Filament\Resources\FakultResource\Pages;

use App\Filament\Resources\FakultResource;
use App\Models\Fakult;
use App\Models\FakultDescription;
use App\Models\Language;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditFakult extends EditRecord
{
    protected static string $resource = FakultResource::class;

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
            $fakultDesctription = FakultDescription::query()
                ->where('fakult_id','=',$record->id)
                ->where('language_id' ,'=', $langId)
                ->first();
            if($fakultDesctription){
                $fakultDesctription->update($language);
            }
        }
        return $record;
    }

    public function fillFormWithDataAndCallHooks(Model $record, array $extraData = []): void
    {
        $fakultDesctriptions = $record->fakultDescription;
        $languages = Language::all();
        $formData = array();
        $formData['url'] = $record->url;
        foreach ($fakultDesctriptions as $fakultDesctription) {
            foreach ($languages as $language) {
                if ($fakultDesctription->language_id == $language->id) {
                    $formData['languages'][$language->id]['name'] = $fakultDesctription['name'];
                }
            }
        }
        $this->form->fill($formData);
    }
}
