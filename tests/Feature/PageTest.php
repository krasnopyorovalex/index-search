<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Page;
use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('page')]
class PageTest extends TestCase
{
    use RefreshDatabase;

    public function test_correct_create_page(): void
    {
        $this->seed();

        $page = Page::query()->first();

        $this->assertDatabaseHas('pages', ['id' => $page->getKey()]);
    }

    public function test_correct_store_column_body_bin(): void
    {
        $content = fake()->randomHtml(maxDepth: 1, maxWidth: 1);

        $page = Page::factory()
            ->has(PageContent::factory()->set('body_gzipped', $content))
            ->create([
                'client_id' => Client::factory()->create()->id,
                'body_bin' => $content,
            ]);

        $page->pageContent->body_gzipped
            |> md5(...)
            |> (fn($x) => $this->assertEquals($x, $page->body_bin));
    }
}
