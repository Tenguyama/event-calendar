<?php

declare(strict_types=1);

namespace App\Repositories\Event;

use App\Data\EventData;
use App\Data\FilterData;
use Spatie\LaravelData\DataCollection;

interface EventRepository
{
    public function getAllByFilters(FilterData $filterData): DataCollection;
    public function save(EventData $eventData): EventData;
    public function delete($id): bool;
}
