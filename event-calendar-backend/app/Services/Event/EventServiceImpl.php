<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Data\EventData;
use App\Data\FilterData;
use App\Repositories\Event\EventRepositoryImpl;
use Spatie\LaravelData\DataCollection;

class EventServiceImpl implements EventService
{

    private static $instances = [];

    private readonly EventRepositoryImpl $repository;

    protected function __construct(
    ) {
        $this->repository = EventRepositoryImpl::getInstance();
    }

    protected function __clone(){}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton EventServiceImpl.");
    }

    public static function getInstance(): EventServiceImpl
    {
        $subclass  = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }


    public function getAllByFilters(FilterData $filterData): DataCollection
    {
       return $this->repository->getAllByFilters($filterData);
    }

    public function save(EventData $eventData): EventData
    {
        return $this->repository->save($eventData);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }
}
