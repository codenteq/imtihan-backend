<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ->create();
        User::factory(1)
            ->state(['email' => 'student@imtihan.tech'])
            ->state(['role' => User::Student])
            ->create();
        Country::factory(1)->create();
        City::factory(1)->create();
        State::factory(1)->create();
    }
}
