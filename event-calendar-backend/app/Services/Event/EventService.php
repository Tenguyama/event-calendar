<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Data\EventData;
use App\Data\FilterData;
use Spatie\LaravelData\DataCollection;

interface EventService
{
    public function getAllByFilters(FilterData $filterData): DataCollection;
    public function save(EventData $eventData): EventData;
    public function delete($id): bool;

}
