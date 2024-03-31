<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\EducationLevel;
use App\Enums\Role;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory(1)
            ->state(['email' => 'admin@imtihan.tech'])
            ->state(['role' => Role::Admin])
            ->create();
        User::factory(1)
            ->state(['email' => 'student@imtihan.tech'])
            ->state(['role' => Role::Student])
            ->state(['education_level' => EducationLevel::High->value])
            ->create();
    }
}
