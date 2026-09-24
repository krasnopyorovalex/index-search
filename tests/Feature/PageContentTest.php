<?php

namespace Tests\Feature;

use App\Jobs\Manticore\StoreRowJob;
use App\Models\Page;
use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('page.content')]
class PageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_pushed_to_queue_store_rows_job()
    {
        Queue::fake();

        $content = fake()->randomHtml(maxDepth: 1, maxWidth: 1);

        PageContent::factory()->create([
            'body_gzipped' => $content,
            'page_id' => Page::factory()->create(['body_bin' => $content])->getKey()
        ]);

        Queue::assertPushedOnce(StoreRowJob::class);
    }
}
