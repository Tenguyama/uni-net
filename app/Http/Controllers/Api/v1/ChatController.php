<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\ChatRequest;
use App\Http\Requests\Chat\ChatWithConsumerRequest;
use App\Http\Requests\Chat\ChatWithMessageRequest;
use App\Models\Chat;
use App\Models\Consumer;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    //-getChatById (and AllMessage)✅
    //-getLastMessages ✅
    //-findOrCreateSoloChat✅
    //-createMultiChat✅
    //-addPermission✅
    //-addConsumerToChat✅
    //-updateChat (name) (if is group chat) ✅
    //-deleteConsumerForChat✅
    //-deleteChat✅

    private readonly ChatService $service;

    public function __construct(
    ) {
        $this->service = ChatService::getInstance();
    }
    public function getChatById(Chat $chat): JsonResponse
    {
        return new JsonResponse($this->service->getChatById($chat), 200);
    }
    public function getLastMessages(ChatWithMessageRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->getLastMessages($request), 200);
    }
    public function findOrCreateSoloChat(Consumer $consumer): JsonResponse
    {
        return new JsonResponse($this->service->findOrCreateSoloChat($consumer), 201);
    }
    public function createMultiChat(ChatRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->createMultiChat($request),201);
    }
    public function addPermission(ChatWithConsumerRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->addPermission($request), 201);
    }
    public function addConsumerToChat(ChatWithConsumerRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->addConsumerToChat($request), 201);
    }
    public function updateChat(ChatRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->updateChat($request), 201);
    }
    public function deleteConsumerForChat(ChatWithConsumerRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->deleteConsumerForChat($request), 204);
    }
    public function deleteChat(Chat $chat): JsonResponse
    {
        return new JsonResponse($this->service->deleteChat($chat), 204);
    }
}
