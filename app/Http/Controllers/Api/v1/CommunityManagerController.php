<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComminityManager\CommunityManagerRequest;
use App\Services\CommunityManagerService;
use Illuminate\Http\JsonResponse;

class CommunityManagerController extends Controller
{
    //create    +
    //delete    +
    private readonly CommunityManagerService $service;

    public function __construct(
    ) {
        $this->service = CommunityManagerService::getInstance();
    }

    public function create(CommunityManagerRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request), 201);
    }

    public function delete(CommunityManagerRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->delete($request),204);
    }
}
