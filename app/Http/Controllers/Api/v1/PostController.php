<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    //getPostById ✅
    //getAllRecommendationPost ✅ - рекомендовані
    //getAllPostByTrackable ✅ - відстежувані
    //create ✅
    //update ✅
    //delete ✅
    private readonly PostService $service;

    public function __construct(
    ) {
        $this->service = PostService::getInstance();
    }

    public function getAllRecommendationPost(){
        return new JsonResponse($this->service->getAllRecommendationPost(), 201);
    }

    public function getAllPostByTrackable(){
        return new JsonResponse($this->service->getAllPostByTrackable(), 201);
    }

    public function getPostById(Post $post){
        return new JsonResponse($this->service->getPostById($post), 201);
    }

    public function create(PostRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request), 201);
    }

    public function update(PostRequest $request, Post $post): JsonResponse
    {
        return new JsonResponse($this->service->update($request, $post), 201);
    }

    public function delete(Post $post): JsonResponse
    {
        return new JsonResponse($this->service->delete($post),204);
    }
}
