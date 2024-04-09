<?php

namespace App\Repositories;

use App\Http\Requests\Message\MessageRequest;
use App\Models\Media;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageRepository
{
    protected static $instance;

    private readonly Message $model;

    protected function __construct(){
        $this->model = new Message();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(MessageRequest $request){
        $consumerId = Auth::user()->id;
        $id = $request->input('id');
        $params = [
            'chat_id' => $request->input('chat_id'),
            'body' => $request->input('body'),
            'consumer_id' => $consumerId,
        ];
        $message = $this->model->query()
            ->where('id','=', $id)
            ->where('consumer_id','=', $consumerId)
            ->first();

        if(isset($message) and !empty($message)){
            $resultMessage = $this->model->query()->updateOrCreate(['id'=>$id], $params);
        }else{
            $resultMessage = $this->model->query()->create($params);
        }

        if ($request->has('media')) {
            $mediaUrl = $request->input('media');
            $media = $resultMessage->media()->first();
            if (isset($media) and !empty($media)) {
                $media->path = $mediaUrl;
                $media->save();
            } else {
                $media = new Media();
                $media->mediable_id = $resultMessage->id;
                $media->mediable_type = $this->model::class;
                $media->path = $mediaUrl;
                $media->save();
            }
        }
        $resultMessage->load('media', 'consumer', 'consumer.media');
        return $resultMessage;
    }

    public function delete(MessageRequest $request){
        $consumerId = Auth::user()->id;
        $id = $request->input('id');
        $params = [
            'chat_id' => $request->input('chat_id'),
            'body' => $request->input('body'),
            'consumer_id' => $consumerId,
        ];

        if (isset($id) and !empty($id)) {
            $message = $this->model->query()
                ->where('id', '=', $id)
                ->where('consumer_id', '=', $params['consumer_id'])
                ->first();
        } elseif (isset($locale) and !empty($locale)) {
            $message = $this->model->query()
                ->where('chat_id', '=', $params['chat_id'])
                ->where('consumer_id', '=', $params['consumer_id'])
                ->where('body', '=', $params['body'])
                ->first();
        }

        if (isset($message) and !empty($message)) {
            return $message->delete();
        } else {
            return ['message' => 'Message not found'];
        }
    }

}
