<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Complaint\ComplaintRequest;
use App\Services\ComplaintService;
use Illuminate\Http\JsonResponse;

class ComplaintController extends Controller
{
    //По великому рахунку для API потрібно лиш:
    //create - створення скарги
    //count - кількість скарг на сутність (коментар або пост)
    //      але count - не дуже, хіба на пост, але де, як і коли показувати,
    //      чи разом з постом а не тут, чи якщо це пост профілю то тільки
    //      користувачеві цього профілю, чи може воно взагалі зайве?
    //
    //обробка скарг буде модером в адмінці бекенду,
    //      (Можливо ПЕРЕДБАЧИТИ ФУНКЦІОНАЛ ПРИХОВАННЯ ПОСТУ\КОМЕНТУ модератором,
    //       але нащо? якщо реально контент не відповідає корпоративним "нормам"
    //       то його одразу модер видяляє при розгляді скарги)
    // а результат обробки - Alert::class\AlertDescription::class\AlertType::class
    // як тип нотифікації по певному шаблону за рішенням модера і тд.
    private readonly ComplaintService $service;

    public function __construct(
    ) {
        $this->service = ComplaintService::getInstance();
    }

    public function create(ComplaintRequest $request): JsonResponse
    {
        return new JsonResponse($this->service->create($request), 200);
    }
}
