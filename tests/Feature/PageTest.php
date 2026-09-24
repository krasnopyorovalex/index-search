<?php

namespace Tests\Feature;

use App\Domain\Crawler\ValueObjects\StrBinary;
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
        Page::factory()->create();

        $page = Page::query()->first();

        $this->assertDatabaseHas('pages', ['id' => $page->getKey()]);
    }

    public function test_correct_store_column_body_bin(): void
    {
        $content = fake()->randomHtml(maxDepth: 1, maxWidth: 1);

        $page = Page::factory()
            ->has(PageContent::factory()->set('body', $content))
            ->create([
                'body_bin' => $content,
            ]);

        $this->assertEquals($content, $page->pageContent->body);
        $this->assertEquals((string)new StrBinary($content)->asMd5(), $page->body_bin);
    }
}
