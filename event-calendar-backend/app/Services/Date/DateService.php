<?php

declare(strict_types=1);

namespace App\Services\Date;

interface DateService
{
    public function getDateRange():array;
    public function getCalendar():array;
}
