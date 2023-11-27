<?php

namespace App\Data;

use App\Enum\EventTypeEnum;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class EventData extends Data
{
    public function __construct(
        #[Uuid()]
        public ?string $id,
        #[Min(3)]
        #[Max(128)]
        public string $name,
        #[Min(3)]
        #[Max(512)]
        public string $description,
        //yyyy-mm-ddThh:mm:00+00:00
        #[DateFormat('Y-m-d\\TH:i:sP')]
        public Carbon $datetime,
        #[Min(3)]
        #[Max(128)]
        public string $location,
        #[Enum(EventTypeEnum::class)]
        public string $type,
    ) {}
}
