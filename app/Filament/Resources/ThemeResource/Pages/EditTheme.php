<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Filament\Resources\ThemeResource;
use App\Models\Language;
use App\Models\ThemeDescription;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTheme extends EditRecord
{
    protected static string $resource = ThemeResource::class;

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
            $themeDesctription = ThemeDescription::query()
                ->where('theme_id','=',$record->id)
                ->where('language_id' ,'=', $langId)
                ->first();

            if($themeDesctription){
                $themeDesctription->update($language);
            }
        }
        return $record;
    }

    public function fillFormWithDataAndCallHooks(Model $record, array $extraData = []): void
    {
        $themeDescriptions = $record->themeDescription;
        $languages = Language::all();
        $formData = array();
        foreach ($themeDescriptions as $themeDescription) {
            foreach ($languages as $language) {
                if ($themeDescription->language_id == $language->id) {
                    $formData['languages'][$language->id]['name'] = $themeDescription['name'];
                }
            }
        }
        $this->form->fill($formData);
    }
}
