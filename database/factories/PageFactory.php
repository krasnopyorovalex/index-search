<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
#[UseModel(Page::class)]
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $client = Client::query()->inRandomOrder()->first();

        return [
            'client_id' => $client ? $client->id : Client::factory()->create()->id,
            'body_bin' => $this->faker->randomHtml()
        ];
    }
}
