<?php

declare(strict_types=1);

namespace App\Services\Date;

use Illuminate\Support\Carbon;

class DateServiceImpl implements DateService
{
    private static $instances = [];

    protected function __construct() {}

    protected function __clone(){}

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton DataServiceImpl.");
    }

    public static function getInstance(): DateServiceImpl
    {
        $subclass  = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static();
        }

        return self::$instances[$subclass];
    }

    public function getDateRange(): array
    {
        $currentDate = Carbon::now();
        $firstDate = $currentDate->firstOfMonth()->format('Y-m-d');
        $lastDate = $currentDate->addMonths(5)->endOfMonth()->format('Y-m-d');
        $dataRange =  [
            'first_date' => $firstDate,
            'last_date' => $lastDate,
        ];
        return $dataRange;

    }

    //тут з днями попереднього і наступного місяців щоб були "повні тижні"
    public function getCalendar(): array
    {
        $dateRange = $this->getDateRange();
        $firstDate = Carbon::parse($dateRange['first_date']);
        $lastDate = Carbon::parse($dateRange['last_date']);

        $calendar = [];

        while ($firstDate->lessThanOrEqualTo($lastDate)) {
            $monthName = $firstDate->format('F');
            $monthStartDate = $firstDate->copy()->firstOfMonth();
            $monthEndDate = $firstDate->copy()->endOfMonth();
            $daysInMonth = $monthStartDate->daysInMonth;
            $month = [
                'name' => $monthName,
                'weeks' => [],
            ];

            $weekNumber = 1;
            $week = [];

            $startDayOfWeek = $monthStartDate->dayOfWeek;

            // Додаємо дні з попереднього місяця
            for ($i = $startDayOfWeek; $i > 0; $i--) {
                $previousDay = $monthStartDate->copy()->subDays($i);
                $week[$previousDay->dayOfWeek] = [
                    'day' => $previousDay->day,
                    'date' => $previousDay->format('Y-m-d'),
                    'active' => false,
                    'disabled' => true,
                ];
            }

            // Перебираємо кожний день місяця
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = $monthStartDate->copy()->addDays($day - 1);
                $dayOfWeek = $currentDate->dayOfWeek;
                $isCurrentMonth = $currentDate->isSameMonth($firstDate);
                $dayData = [
                    'day' => $day,
                    'date' => $currentDate->format('Y-m-d'),
                    'active' => $isCurrentMonth,
                    'disabled' => !$isCurrentMonth,
                    ];

                $week[$dayOfWeek] = $dayData;

                // Переходимо до нового тижня
                if ($dayOfWeek === 6 || $day === $daysInMonth) {
                    if ($dayOfWeek < 6 && $day === $daysInMonth) {
                        // Додаємо дні наступного місяця в останній тиждень
                        $nextMonthFirstDay = $monthEndDate->copy()->addDay();
                        for ($i = 0; $i < 6 - $dayOfWeek; $i++) {
                            $nextDay = $nextMonthFirstDay->copy()->addDays($i);
                            $week[$nextDay->dayOfWeek] = [
                                'day' => $nextDay->day,
                                'date' => $nextDay->format('Y-m-d'),
                                'active' => false,
                                'disabled' => true,
                            ];
                        }
                    }

                    // Додаємо тиждень до масиву тижнів
                    $month['weeks'][$weekNumber] = $week;
                    $weekNumber++;
                    $week = [];
                }
            }

            $firstDate->addMonth();
            $calendar[] = $month;
        }
        return  $calendar;
    }
    //тільки дні поточного місяця, якщо не справлюсь з "повними тижнями"
//    public function getCalendar(): array
//    {
//        // Отримуємо діапазон дат
//        $dateRange = $this->getDateRange();
//        $firstDate = Carbon::parse($dateRange['first_date']);
//        $lastDate = Carbon::parse($dateRange['last_date']);
//
//        $calendar = [];
//
//        // Проходимо через кожен місяць у діапазоні
//        while ($firstDate->lessThanOrEqualTo($lastDate)) {
//            // Отримуємо назву місяця та дати початку та кінця місяця
//            $monthName = $firstDate->format('F');
//            $monthStartDate = $firstDate->copy()->firstOfMonth();
//            $daysInMonth = $monthStartDate->daysInMonth;
//
//            $month = [
//                'name' => $monthName,
//                'weeks' => [],
//            ];
//
//            $weekNumber = 1;
//            $week = [];
//
//            // Проходимо через кожен день у місяці
//            for ($day = 1; $day <= $daysInMonth; $day++) {
//                $currentDate = $monthStartDate->copy()->addDays($day - 1);
//                $dayOfWeek = $currentDate->dayOfWeek;
//
//                $dayData = ['day' => $day];
//
//                $week[$dayOfWeek] = $dayData;
//
//                // Якщо день є останнім днем тижня або останнім днем місяця, додаємо тиждень до місяця
//                if ($dayOfWeek === 6 || $day === $daysInMonth) {
//                    $month['weeks'][$weekNumber] = $week;
//                    $weekNumber++;
//                    $week = [];
//                }
//
//            }
//
//            $firstDate->addMonth();
//
//            $calendar[] = $month;
//        }
//
//        return $calendar;
//    }
}
