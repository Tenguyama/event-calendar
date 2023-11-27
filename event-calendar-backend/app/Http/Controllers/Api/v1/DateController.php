<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\Date\DateServiceImpl;
use Illuminate\Http\JsonResponse;

class DateController extends Controller
{
    private readonly DateServiceImpl $service;

    public function __construct(
    ) {
        $this->service = DateServiceImpl::getInstance();
    }

    public function getDateRange(): JsonResponse
    {
        return new JsonResponse($this->service->getDateRange());
    }

    public function getCalendar(): JsonResponse
    {
        return new JsonResponse($this->service->getCalendar());
    }
}
