<?php

namespace Database\Factories;

use App\Models\Page;
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
            'page_id' => Page::factory(),
            'url' => $this->faker->url(),
        ];
    }
}
