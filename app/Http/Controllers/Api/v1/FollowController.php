<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Follow\FollowRequest;
use App\Models\Consumer;
use App\Services\FollowService;
use Illuminate\Http\JsonResponse;

class FollowController extends Controller
{
    //follow  ✅ - якщо нема то створити якщо є то видалити
    //getTrackable✅(підписки користувача)
    //getFollowers✅(підписки на користувача\групу)
    private readonly FollowService $service;

    public function __construct(
    ) {
        $this->service = FollowService::getInstance();
    }

    public function follow(FollowRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->follow($request), 201);
    }

    public function getFollowers(FollowRequest $request)
    {
        return new JsonResponse($this->service->getFollowers($request), 200);
    }

    public function getTrackable(Consumer $consumer)
    {
        return new JsonResponse($this->service->getTrackable($consumer), 200);
    }
}
