<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditComplaint extends EditRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // тут обробка всієї штуки
        // якщо в формі - рішення видалять - то видаляю і ставлю четек тру
        // якщо в формі - рішення не видалять -  ставлю четек тру
//        $languages = $data['languages'];
//        unset($data['languages']);
//        $record->update($data);
//        foreach ($languages as $langId => $language) {
//            $groupDescription = GroupDescription::query()
//                ->where('group_id','=',$record->id)
//                ->where('language_id' ,'=', $langId)
//                ->first();
//            if($groupDescription){
//                $groupDescription->update($language);
//            }
//        }



        // Tables\Columns\TextColumn::make('id')
        //                    ->label('ID')
        //                    ->searchable(),
        //                Tables\Columns\IconColumn::make('is_checked')
        //                    ->sortable()
        //                    ->boolean(),
        //                Tables\Columns\TextColumn::make('complaintable_type')
        //                    ->searchable(),
        //                Tables\Columns\TextColumn::make('description')
        //                    ->searchable(),
        //                Tables\Columns\TextColumn::make('created_at')
        //                    ->dateTime()
        //                    ->sortable()
        //                    ->toggleable(isToggledHiddenByDefault: true),
        //                Tables\Columns\TextColumn::make('updated_at')
        //                    ->dateTime()
        //                    ->sortable()
        //                    ->toggleable(isToggledHiddenByDefault: true),
        return $record;
    }

    public function fillFormWithDataAndCallHooks(Model $record, array $extraData = []): void
    {
//        ('complaintable_type')
//        ('complaintable_data')
//        ('consumer_data')
//        ('description')
//        ('is_delete')
//        ('is_checked')
//        ('clear')
//

        $complaintable = $record->complaintable;
        dd();

//        $groupDescriptions = $record->groupDescription;
//        $languages = Language::all();
//        $formData = array();
//        $formData['fakult_id'] = $record->fakult_id;
//        foreach ($groupDescriptions as $groupDescription) {
//            foreach ($languages as $language) {
//                if ($groupDescription->language_id == $language->id) {
//                    $formData['languages'][$language->id]['name'] = $groupDescription['name'];
//                }
//            }
//        }
        $this->form->fill($formData);
    }
}
