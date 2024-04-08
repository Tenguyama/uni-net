<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    //create ✅
    //update ✅
    //delete ✅
    private readonly CommentService $service;

    public function __construct(
    ) {
        $this->service = CommentService::getInstance();
    }

    public function create(CommentRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request), 201);
    }

    public function update(CommentRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->update($request), 201);
    }

    public function delete(Comment $comment): JsonResponse
    {
        return new JsonResponse($this->service->delete($comment),204);
    }
}
