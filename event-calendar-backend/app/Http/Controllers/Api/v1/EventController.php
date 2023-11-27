<?php

namespace App\Http\Controllers\Api\v1;

use App\Data\EventData;
use App\Data\FilterData;
use App\Http\Controllers\Controller;
use App\Services\Event\EventServiceImpl;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    private readonly EventServiceImpl $service;

    public function __construct(
    ) {
        $this->service = EventServiceImpl::getInstance();
    }

    public function getAllByFilters(FilterData $filterData): JsonResponse
    {
        return new JsonResponse($this->service->getAllByFilters($filterData), 200);
    }

    public function create(EventData $eventData): JsonResponse
    {
        return new JsonResponse($this->service->save($eventData), 200);
    }

    public function update(EventData $eventData): JsonResponse
    {
        return new JsonResponse($this->service->save($eventData), 200);
    }

    public function delete($id): JsonResponse
    {
        return new JsonResponse($this->service->delete($id), 200);
    }
}
