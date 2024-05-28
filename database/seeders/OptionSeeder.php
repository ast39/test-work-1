<?php

namespace Database\Seeders;

use App\Enums\ESoftStatus;
use App\Models\Option;
use Illuminate\Database\Seeder;


class OptionSeeder extends Seeder {

    public function run(): void
    {
        Option::query()->create(['abbr' => 'color', 'title' => 'Цвет']);
        Option::query()->create(['abbr' => 'weight', 'title' => 'Вес']);
        Option::query()->create(['abbr' => 'length', 'title' => 'Длина']);
        Option::query()->create(['abbr' => 'width', 'title' => 'Ширина']);
        Option::query()->create(['abbr' => 'height', 'title' => 'Толщина']);
    }
}
