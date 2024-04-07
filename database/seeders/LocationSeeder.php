<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = File::json(base_path('/database/dump-data/countries.json'));
        $cities = File::json(base_path('/database/dump-data/cities.json'));
        $states = File::json(base_path('/database/dump-data/states.json'));

        DB::transaction(function () use ($countries, $cities, $states) {
            foreach ($countries as $country) {
                Country::create($country);
            }

            foreach ($states as $state) {
                State::create($state);
            }

            foreach ($cities as $city) {
                City::create($city);
            }
        });
    }
}
