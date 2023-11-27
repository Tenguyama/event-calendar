<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class FilterData extends Data
{
    public function __construct(
        // масив типів заходів з фільтрів [ "expert", "qa", "conference", "webinar" ] (один, декілька, або всі)
        #[ArrayType]
        public array $filters,
        // перша дата поточного (1-го) місяця , остання дата останнього (6-го від поточного) місяця
        // не перевіряю значення, бо я сам їх генерую типу
        #[ArrayType('first_date', 'last_date')]
        public array $dateRange
    ) {}
}
