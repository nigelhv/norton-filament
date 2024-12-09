<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Activity;
use App\Models\Location;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        User::factory()->create([
            'first_name' => 'Nigel',
            'surname' => 'Hagger-Vaughan',
            'email' => 'nigelhv@gmail.com',
            'location_id' => '1',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        User::factory(230)->create();
        echo "created 230 users\n";

        Location::factory()->create([
            'id' => '1',
            'location' => 'Worcester'
        ]);
        Location::factory()->create([
            'id' => '2',
            'location' => 'Tewkesbury'
        ]);
        echo "created locations\n";

        Student::factory(280)->create();
        echo "created 280 students\n";
        Subject::factory()->create([
            'name' => 'English',
        ]);
        echo "added English\n";
        Subject::factory()->create([
            'name' => 'Maths',
        ]);
        echo "added maths\n";
        Subject::factory()->create([
            'name' => 'PSHE',
        ]);
        echo "added PSHE\n";
        Subject::factory(200)->create();
        echo "created 200 subjects\n";
        echo "creating activities\n";
        Activity::factory(100000)
            ->create();
        echo "created 100,000 activities\n";
    }
}
