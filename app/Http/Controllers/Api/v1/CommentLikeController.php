<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentLike\CommentLikeRequest;
use App\Http\Requests\Consumer\ConsumerRequest;
use App\Services\CommentLikeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentLikeController extends Controller
{
    //create    +
    //update    +
    //delete    +
    private readonly CommentLikeService $service;

    public function __construct(
    ) {
        $this->service = CommentLikeService::getInstance();
    }

    public function create(CommentLikeRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request), 201);
    }

    public function update(CommentLikeRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->update($request), 201);
    }

    public function delete(CommentLikeRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->delete($request),204);
    }
}
