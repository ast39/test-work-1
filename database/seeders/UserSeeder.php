<?php

namespace Database\Seeders;

use App\Enums\ESoftStatus;
use App\Enums\EUserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder {

    public function run(): void
    {
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
            'status' => ESoftStatus::ACTIVE->value,
        ]);
    }
}
