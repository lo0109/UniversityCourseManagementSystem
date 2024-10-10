<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => 'ICT' . $this->faker->unique()->randomNumber(4, true),  // Unique course ID
            'name' => 'Course '.$this->faker->unique()->randomNumber(1),  // Course name
            'description' => 'Course Description'.$this->faker->paragraph(1),  // Course description
            'workshop' => $this->faker->numberBetween(2,5),  // Random number between 1 and 5
            'online' => $this->faker->boolean(),  // Random boolean for online
            'teacherID' => User::where('teacher', true)->inRandomOrder()->first()->userID,  // Assign a teacher ID
        ];
    }
}
