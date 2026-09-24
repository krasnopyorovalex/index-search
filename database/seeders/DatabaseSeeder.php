<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Init User',
            'email' => 'init@ya.ru',
        ]);

        $this->call([
            ClientsSeeder::class,
            PageSeeder::class,
        ]);

        $this->call([
            PageSeeder::class,
        ]);
    }
}
