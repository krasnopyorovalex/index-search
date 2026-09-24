<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageContent>
 */
#[UseModel(PageContent::class)]
class PageContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->realText(50),
            'body_gzipped' => $this->faker->randomHtml(),
            'page_id' => Page::factory(),
        ];
    }
}
