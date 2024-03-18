<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Consumer\ConsumerLoginRequest;
use App\Http\Requests\Consumer\ConsumerRequest;
use App\Http\Requests\Consumer\ConsumerSocialiteRequest;
use App\Http\Requests\SearchRequest;
use App\Services\ConsumerService;
use Illuminate\Http\JsonResponse;

class ConsumerController extends Controller
{
    //registerWithProvider  +
    //loginWithProvider     +
    //login                 +
    //logout                +
    //update                +
    //delete                +
    //search                +
    private readonly ConsumerService $service;

    public function __construct(
    ) {
        $this->service = ConsumerService::getInstance();
    }
    //доробити щоб з аватарками робило + (no testing)
    public function registerWithProvider(ConsumerSocialiteRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->registerWithProvider($request), 201);
    }
    public function loginWithProvider(ConsumerSocialiteRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->loginWithProvider($request), 201);
    }
    public function login(ConsumerLoginRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->login($request),201);
    }
    public function logout(): JsonResponse
    {
        return new JsonResponse($this->service->logout(),204);
    }
    //доробити щоб з аватарками вертало + (no testing)
    public function update(ConsumerRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->update($request), 201);
    }
    public function delete(): JsonResponse
    {
       return new JsonResponse($this->service->delete(),204);
    }


    //доробити щоб з аватарками вертало + (no testing)
    public function search(SearchRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->search($request), 200);
    }
}
