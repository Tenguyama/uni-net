<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostLike\PostLikeRequest;
use App\Services\PostLikeService;
use Illuminate\Http\JsonResponse;

class PostLikeController extends Controller
{
    //create    ✅
    //update    ✅
    //delete    ✅
    private readonly PostLikeService $service;

    public function __construct(
    ) {
        $this->service = PostLikeService::getInstance();
    }

    public function create(PostLikeRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request), 201);
    }

    public function update(PostLikeRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->update($request), 201);
    }

    public function delete(PostLikeRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->delete($request),204);
    }
}
