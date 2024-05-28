<?php

namespace Database\Seeders;

use App\Enums\ESoftStatus;
use App\Models\Item;
use Illuminate\Database\Seeder;


class ItemSeeder extends Seeder {

    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {

             $item = Item::create([
                'title' => 'Test item ' . $i,
                'body' => 'Test item' . $i,
                'price' => rand(100, 5000),
                'stock' => rand(1, 100),
                'status' => ESoftStatus::ACTIVE->value,
            ]);

             $item->options()->attach([
                 1 => ['value' => $this->generateColor()],
                 2 => ['value' => rand(50, 1000)],
                 3 => ['value' => rand(10, 50)],
                 4 => ['value' => rand(10, 50)],
                 5 => ['value' => rand(5, 20)],
             ]);
        }
    }

    private function generateColor(): string
    {
        $colors = [
            'красный',
            'синий',
            'зеленый',
            'черный',
            'белый',
        ];

        shuffle($colors);

       return $colors[rand(0, 4)];
    }
}
