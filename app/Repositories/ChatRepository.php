<?php

namespace App\Repositories;

use App\Http\Requests\Chat\ChatRequest;
use App\Http\Requests\Chat\ChatWithConsumerRequest;
use App\Http\Requests\Chat\ChatWithMessageRequest;
use App\Models\Chat;
use App\Models\Consumer;
use App\Models\ConsumerChat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatRepository
{
    protected static $instance;

    private readonly Chat $model;
    private readonly Consumer $consumer;
    private readonly ConsumerChat $consumerChat;
    private readonly Message $message;
    protected function __construct(){
        $this->model = new Chat();
        $this->consumer = new Consumer();
        $this->consumerChat = new ConsumerChat();
        $this->message = new Message();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getChatById(Chat $chat)
    {
        $consumerId = Auth::user()->id;
        $is_exist = $this->consumerChat->query()
            ->where('consumer_id','=', $consumerId)
            ->where('chat_id','=',$chat->id)
            ->exists();

        if($is_exist){
            $chat->load('consumerChats' , 'messages', 'messages.consumer', 'messages.media');
            return $chat;
        }else{
            return ['message' => 'Consumer not have permission'];
        }
    }
    public function getLastMessages(ChatWithMessageRequest $request){
        $consumerId = Auth::user()->id;
        $params = [
            'chat_id' => $request->input('chat_id'),
            'message_id' => $request->input('message_id'),
        ];

        $hasPermission = $this->consumerChat->query()
            ->where('consumer_id','=', $consumerId)
            ->where('chat_id','=',$params['chat_id'])
            ->exists();

        if($hasPermission){
            $message = $this->message->query()
                ->where('id','=',$params['message_id'])
                ->where('chat_id','=',$params['chat_id'])
                ->first();

            if($message){
                $chat = $this->model->query()
                    ->where('id', $params['chat_id'])
                    ->with(['messages' => function ($query) use ($message) {
                        $query->where('created_at', '>', $message->created_at)
                            ->orderBy('created_at', 'asc')
                            ->with('consumer', 'media');
                    }])
                    ->first();
                return $chat;
            }else{
                return ['message' => 'Message not found'];
            }
        }else{
            return ['message' => 'Consumer not have permission'];
        }
    }
    public function findOrCreateSoloChat(Consumer $consumer)
    {
        $consumerId = Auth::user()->id;
        $authConsumer = $this->consumer->query()
            ->where('id','=',$consumerId)
            ->first();

        $chat = $this->model->query()->where('type', 'solo')
            ->whereHas('consumerChats', function ($query) use ($consumerId) {
                $query->where('consumer_id', $consumerId);
            })
            ->whereHas('consumerChats', function ($query) use ($consumer) {
                $query->where('consumer_id', $consumer->id);
            })
            ->first();

        if ($chat) {
            $chat->load('consumerChats' , 'messages', 'messages.consumer', 'messages.media');
            return $chat;
        }

        // Якщо чат не знайдено, створюємо новий
        $newChat = new Chat;
        $newChat->name = $authConsumer->nickname .' |->| '. $consumer->nickname;
        $newChat->type = 'solo';
        $newChat->save();

        // Додавання користувачів до чату
        $newChat->consumerChats()->createMany([
            ['consumer_id' => $authConsumer->id, 'is_creator' => true, 'is_admin' => true],
            ['consumer_id' => $consumer->id, 'is_creator' => true, 'is_admin' => true]
        ]);

        $newChat->load('consumerChats' , 'messages', 'messages.consumer', 'messages.media');
        return $newChat;
    }

    public function createMultiChat(ChatRequest $request)
    {
        $consumerId = Auth::user()->id;
        $params = [
            'name' => $request->input('name'),
        ];
        $chat = $this->model->query()
            ->create([
                'name' => $params['name'],
                'type' => 'multi',
            ]);
        $creator = $this->consumerChat->query()
            ->create([
              'chat_id' => $params['chat_id'],
              'consumer_id'=> $consumerId,
              'is_creator'=> true,
              'is_admin'=> true,
            ]);
        return [
          'chat' => $chat,
          'creator' => $creator,
        ];
    }
    public function addPermission(ChatWithConsumerRequest $request)
    {
        $consumerId = Auth::user()->id;
        $params = [
            'chat_id' => $request->input('chat_id'),
            'consumer_id' => $request->input('consumer_id'),
        ];

        $chat = $this->model->query()
            ->where('id', '=', $params['chat_id'])
            ->first();
        if ($chat->type == 'multi'){
            $permissions = $chat->consumerChats()
                ->where('consumer_id', '=', $consumerId)
                ->first();
            $addedConsumerExists = $chat->consumerChats()
                ->where('consumer_id', '=', $params['consumer_id'])
                ->exists();

            if (!$permissions or !$addedConsumerExists) {
                return ['message' => 'Consumer dont have in chat or you do not have permission'];
            }

            $addedConsumer = $this->consumerChat->query()
                ->where('consumer_id', '=', $params['consumer_id'])
                ->first();

            if ($permissions->is_creator or $permissions->is_admin) {
                if ($addedConsumer) {
                    $addedConsumer->is_admin = true;
                    $addedConsumer->save();
                    return $addedConsumer;
                } else {
                    return ['message' => 'Consumer not found'];
                }
            } else {
                return ['message' => 'You do not have permission to add this consumer in this chat'];
            }
        }else{
            return ['message' => 'Is not multi type Chat'];
        }
    }
    public function addConsumerToChat(ChatWithConsumerRequest $request){
        $consumerId = Auth::user()->id;
        $params = [
            'chat_id' => $request->input('chat_id'),
            'consumer_id' => $request->input('consumer_id'),
        ];

        $chat = $this->model->query()
            ->where('id', '=', $params['chat_id'])
            ->first();
        if ($chat->type == 'multi'){
            $permissions = $chat->consumerChats()
                ->where('consumer_id', '=', $consumerId)
                ->first();
            $addedConsumerExists = $chat->consumerChats()
                ->where('consumer_id', '=', $params['consumer_id'])
                ->exists();

            if (!$permissions or $addedConsumerExists) {
                return ['message' => 'Consumer have in chat or you do not have permission'];
            }

            if ($permissions->is_creator or $permissions->is_admin) {
                return $this->consumerChat->query()
                    ->create(
                        $params
                            // якщо default в міграції не спрацює
                            //[
                            //  'chat_id' => $params['chat_id'],
                            //  'consumer_id'=> $params['consumer_id'],
                            //  'is_creator'=> false,
                            //  'is_admin'=> false,
                            //]
                    );
            } else {
                return ['message' => 'You do not have permission to add this consumer in this chat'];
            }
        }else{
            return ['message' => 'Is not multi type Chat'];
        }
    }

    public function updateChat(ChatRequest $request){
        $consumerId = Auth::user()->id;
        $params = [
            'chat_id' => $request->input('id'),
            'name' => $request->input('name'),
        ];

        $chat = $this->model->query()
            ->where('id', '=', $params['chat_id'])
            ->first();

        if ($chat->type == 'multi'){
            $permissions = $chat->consumerChats()
                ->where('consumer_id', '=', $consumerId)
                ->first();

            if (!$permissions) {
                return ['message' => 'Consumer not found or you do not have permission'];
            }

            if ($permissions->is_creator or $permissions->is_admin) {
                $chat->name = $params['name'];
                $chat->save();
                return $chat;
            } else {
                return ['message' => 'You do not have permission to update this chat'];
            }

        }else{
            return ['message' => 'Is not multi type Chat'];
        }
    }

    public function deleteConsumerForChat(ChatWithConsumerRequest $request)
    {
        $consumerId = Auth::user()->id;
        $params = [
            'chat_id' => $request->input('chat_id'),
            'consumer_id' => $request->input('consumer_id'),
        ];

        $chat = $this->model->query()
            ->where('id', '=', $params['chat_id'])
            ->first();
        if ($chat->type == 'multi'){
            $permissions = $chat->consumerChats()
                ->where('consumer_id', '=', $consumerId)
                ->first();
            $deletedConsumer = $chat->consumerChats()
                ->where('consumer_id', '=', $params['consumer_id'])
                ->first();

            if (!$permissions or !$deletedConsumer) {
                return ['message' => 'Consumer not found or you do not have permission'];
            }

            if ($permissions->is_creator) {
                if ($deletedConsumer->is_creator) {
                    return ['message' => 'You do not have permission to remove this consumer from this chat'];
                } else {
                    return $deletedConsumer->delete();
                }
            } elseif ($permissions->is_admin) {
                if ($deletedConsumer->is_creator or $deletedConsumer->is_admin) {
                    return ['message' => 'You do not have permission to remove this consumer from this chat'];
                } else {
                    return $deletedConsumer->delete();
                }
            } else {
                return ['message' => 'You do not have permission to remove this consumer from this chat'];
            }
        }else{
            return ['message' => 'Is not multi type Chat'];
        }
    }

    public function deleteChat(Chat $chat) {
        $consumerId = Auth::user()->id;
        $isCreator = $chat->consumerChats()
            ->where('consumer_id', $consumerId)
            ->where('is_creator', true)
            ->exists();

        if ($isCreator) {
            return $chat->delete();
        }else{
            return ['message' => 'This Consumer does not have permission to delete this chat'];
        }
    }
}
