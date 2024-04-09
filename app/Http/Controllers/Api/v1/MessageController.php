<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\MessageRequest;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    //create ✅
    //update ✅
    //delete ✅

    private readonly MessageService $service;

    public function __construct(
    ) {
        $this->service = MessageService::getInstance();
    }

    public function create(MessageRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request),201);
    }
    public function update(MessageRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->update($request),201);
    }
    public function delete(MessageRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->delete($request),204);
    }
}
