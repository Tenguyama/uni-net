<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\GroupGetRequest;
use App\Models\Fakult;
use App\Models\Group;
use App\Models\Language;
use App\Services\FakultService;
use App\Services\GroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    //getAll ✅
    //select ✅ (create or update consumer->group_id)

    private readonly GroupService $service;

    public function __construct(
    ) {
        $this->service = GroupService::getInstance();
    }

    public function getAll(GroupGetRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->getAll($request), 200);
    }

    public function select(Group $group): JsonResponse
    {
        return new JsonResponse($this->service->select($group), 201);
    }
}
