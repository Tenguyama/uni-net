<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use App\Models\Comment;
use App\Models\Consumer;
use App\Models\Post;
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
        $is_delete = false;
        if (!$data['is_delete']){
            if($data['clear']){
                $is_delete = true;
            }
        }
        if ($is_delete){
            $complaintable = $record->complaintable;
            $complaintable->delete();
        }
        $record->is_checked = true;
        $record->save();
        return $record;
    }

    public function fillFormWithDataAndCallHooks(Model $record, array $extraData = []): void
    {
        $consumer = Consumer::query()
            ->where('id','=',$record->consumer_id)
            ->first();

        $complaintable = $record->complaintable;
        if(!empty($complaintable)){
            $complaintable->load(['media']);
            $complaintable->consumer = Consumer::query()
                ->where('id','=',$complaintable->consumer_id)
                ->first();
        }

//        unset($complaintable->consumer);

        if(isset($complaintable->postable_id)){
            $complaintable_type = Post::class;
        }else{
            $complaintable_type = Comment::class;
        }

        $model_type = new $complaintable_type();
        $is_delete = $model_type->query()->where('id','=',$record->complaintable_id)->exists();

        $formData = [
            'complaintable_type' => $complaintable_type,
            'complaintable_data' => json_encode($complaintable, JSON_PRETTY_PRINT), // Форматування JSON у багаторядковий вигляд
            'consumer_data' => json_encode($consumer, JSON_PRETTY_PRINT), // Форматування JSON у багаторядковий вигляд
            'description' => $record->description,
            'is_delete' => !$is_delete,
            'is_checked' => (bool)$record->is_checked,
            'clear' => !$is_delete,
        ];

        $this->form->fill($formData);
    }
}
