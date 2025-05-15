<?php

namespace Database\Seeders;

use App\Models\Cast;
use App\Models\FIlm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FIlm::factory(10)->create();

        Film::all()->each(function ($film) {
            $film->cast()->saveMany(Cast::factory(3)->make());
        });
    }
}
