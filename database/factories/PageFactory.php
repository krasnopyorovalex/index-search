<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\PageUrl;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
#[UseModel(Page::class)]
class PageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_url_id' => PageUrl::factory(),
            'body_bin' => $this->faker->randomHtml()
        ];
    }
}
