<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\LanguageService;
use Illuminate\Http\JsonResponse;

class LanguageController extends Controller
{
    //ONLY FOR ADMIN PANEL
    //create ✅
    //update ✅
    //delete ✅

    //API
    //getAll ✅
    private readonly LanguageService $service;

    public function __construct(
    ) {
        $this->service = LanguageService::getInstance();
    }
    public function getAll(){
        return new JsonResponse($this->service->getAll(), 200);
    }

}
