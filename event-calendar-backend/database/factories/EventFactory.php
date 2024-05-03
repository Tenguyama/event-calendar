<?php

namespace Database\Factories;

use App\Enum\EventTypeEnum;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->words(3, true)),
            'description' => fake()->paragraph(),
            'datetime' => fake()->dateTimeBetween('2024-04-01','2024-10-30'),
            'location' => ucfirst(fake()->word()) . ', ' . fake()->city() . ', ' . fake()->country(),
            'type' => fake()->randomElement([
                EventTypeEnum::MeetingWithAnExpert,
                EventTypeEnum::QuestionAnswer,
                EventTypeEnum::Conference,
                EventTypeEnum::Webinar
            ]),
        ];
    }
}
