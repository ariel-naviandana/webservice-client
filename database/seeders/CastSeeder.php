<?php

namespace Database\Seeders;

use App\Models\Cast;
use App\Models\Film;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $films = Film::factory(5)->create();

        Cast::factory(10)->make()->each(function ($cast) use ($films) {
            $cast->film()->associate($films->random());
            $cast->save();
        });
    }
}
