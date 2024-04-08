<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Fakult;
use App\Models\Language;
use App\Services\FakultService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FakultController extends Controller
{
    //getAllByLanguage ✅
    //select✅ (create or update consumer->fakult_id)

    private readonly FakultService $service;

    public function __construct(
    ) {
        $this->service = FakultService::getInstance();
    }

    public function getAllByLanguage(Language $language): JsonResponse
    {
        return new JsonResponse($this->service->getAllByLanguage($language), 200);
    }

    public function select(Fakult $fakult): JsonResponse
    {
        return new JsonResponse($this->service->select($fakult), 201);
    }
}
