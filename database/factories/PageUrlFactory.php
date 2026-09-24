<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\PageUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageUrl>
 */
class PageUrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url(),
            'client_id' => Client::factory(),
        ];
    }
}
