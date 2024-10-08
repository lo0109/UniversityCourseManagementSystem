<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::inRandomOrder()->first()->course_id,  // Random course ID
            'typeID' => 1,  // Assign a static type ID, modify as necessary
            'title' => 'This is Title'.$this->faker->sentence(1),  // Random assessment title
            'instruction' => 'This is instruction.'.$this->faker->sentence(1),  // Random instructions
            'maxScore' => $this->faker->numberBetween(50, 100),  // Random max score
            'deadline' => $this->faker->dateTimeBetween('now', '+1 month'),  // Future deadline
            'reviewNumber' => $this->faker->numberBetween(3, 5),  // Random number of reviews
        ];
    }
}
