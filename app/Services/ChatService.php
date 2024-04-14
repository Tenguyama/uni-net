<?php

namespace App\Services;

use App\Http\Requests\Chat\ChatRequest;
use App\Http\Requests\Chat\ChatWithConsumerRequest;
use App\Http\Requests\Chat\ChatWithMessageRequest;
use App\Models\Chat;
use App\Models\Consumer;
use App\Repositories\ChatRepository;

class ChatService
{
    protected static $instance;

    private readonly ChatRepository $repository;

    protected function __construct(){
        $this->repository = ChatRepository::getInstance();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getChatById(Chat $chat){
        return $this->repository->getChatById($chat);
    }
    public function getLastMessages(ChatWithMessageRequest $request){
        return $this->repository->getLastMessages($request);
    }
    public function findOrCreateSoloChat(Consumer $consumer){
        return $this->repository->findOrCreateSoloChat($consumer);
    }
    public function createMultiChat(ChatRequest $request){
        return $this->repository->createMultiChat($request);
    }
    public function addPermission(ChatWithConsumerRequest $request){
        return $this->repository->addPermission($request);
    }
    public function addConsumerToChat(ChatWithConsumerRequest $request){
        return $this->repository->addConsumerToChat($request);
    }
    public function updateChat(ChatRequest $request){
        return $this->repository->updateChat($request);
    }
    public function deleteConsumerForChat(ChatWithConsumerRequest $request){
        return $this->repository->deleteConsumerForChat($request);
    }
    public function deleteChat(Chat $chat) {
        return $this->repository->deleteChat($chat);
    }
}
