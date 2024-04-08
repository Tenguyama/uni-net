<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Services\ThemeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    //getAllByLanguage

    private readonly ThemeService $service;

    public function __construct(
    ) {
        $this->service = ThemeService::getInstance();
    }

    public function getAllByLanguage(Language $language): JsonResponse
    {
        return new JsonResponse($this->service->getAllByLanguage($language), 200);
    }
}
