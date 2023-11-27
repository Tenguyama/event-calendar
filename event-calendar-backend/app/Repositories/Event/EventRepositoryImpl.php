<?php

declare(strict_types=1);

namespace App\Repositories\Event;

use App\Data\EventData;
use App\Data\FilterData;
use App\Models\Event;
use Spatie\LaravelData\DataCollection;

class EventRepositoryImpl implements EventRepository
{
    private static $instances = [];
    private readonly Event $model;
    protected function __construct(){
        $this->model = new Event();
    }

    protected function __clone(){}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton EventRepositoryImpl.");
    }

    public static function getInstance(): EventRepositoryImpl
    {
        $subclass  = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }


    public function getAllByFilters(FilterData $filterData): DataCollection
    {
        return EventData::collection(
            $this->model
                ->query()
                ->whereIn('type', $filterData->filters)
                ->whereBetween('datetime', [$filterData->dateRange['first_date'], $filterData->dateRange['last_date']])
                ->get()
                ->toArray());
    }

    public function save(EventData $eventData): EventData
    {
        $id = $eventData->id;
        $array = $eventData->toArray();

        unset($array['id']);
        if (is_null($id)) {
            $event = $this->model->query()->create($array);
        } else {
            $event = $this->model->query()->updateOrCreate(['id' => $id], $array);
        }
        $eventData->id = $event->id;
        return $eventData;
    }

    public function delete($id): bool
    {
        $event = $this->model->query()->find($id);
        if ($event) {
            return $event->delete();
        }
        return false;
    }
}
