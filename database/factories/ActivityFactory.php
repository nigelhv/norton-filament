<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Student;

use App\Models\Subject;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'date' => $this->faker->dateTimeThisDecade,
            'location_id' => mt_rand(1, 2),
            'maths_extra_credit' => mt_rand(0, 2),
            'english_extra_credit' => mt_rand(0, 2),
            'pshe_extra_credit' => mt_rand(0, 2),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Activity $activity) {
            $students = Student::where('location_id', $activity->location_id)->inRandomOrder()->limit(mt_rand(1, 3))->get();
            // commented out reference to global scope
            // $students = Student::withoutGlobalScope(StudentScope::class)->inRandomOrder()->limit(3)->get();
            $activity->students()->sync($students->pluck('id'));
            $subjects = Subject::inRandomOrder()->limit(mt_rand(1, 3))->get();
            $activity->subjects()->sync($subjects->pluck('id'));
            $users = User::where('location_id', $activity->location_id)->inRandomOrder()->limit(1)->get();
            $activity->users()->sync($users->pluck('id'));
        });
    }
}
