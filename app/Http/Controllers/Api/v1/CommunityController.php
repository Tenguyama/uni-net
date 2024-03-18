<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\CommunityRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Community;
use App\Services\CommunityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    //create    +
    //update    +
    //delete    +
    //search    +
    private readonly CommunityService $service;

    public function __construct(
    ) {
        $this->service = CommunityService::getInstance();
    }
    //доробити щоб з аватарками робило
    public function create(CommunityRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request), 201);
    }
    //доробити щоб з аватарками робило
    public function update(CommunityRequest $request, string $id): JsonResponse
    {
        return new JsonResponse($this->service->update($request, $id), 201);
    }

    public function delete(Community $community): JsonResponse
    {
        return new JsonResponse($this->service->delete($community),204);
    }

    //доробити щоб з аватарками вертало
    public function search(SearchRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->search($request), 200);
    }
}
