<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageContent;
use App\Models\PageUrl;
use fivefilters\Readability\ParseException;
use Illuminate\Database\Seeder;
use Pforret\PfArticleExtractor\ArticleExtractor;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws ParseException
     */
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            $html = file_get_contents(__DIR__ . '/stubs/raw_html_' . $i . '.html');

            $pageHtml = ArticleExtractor::getArticle($html);

            $page = Page::factory()->create(['body_bin' => $pageHtml->content]);

            PageContent::factory()->for($page, 'page')->create([
                'title' => $pageHtml->title,
                'body_gzipped' => $pageHtml->content
            ]);

            PageUrl::factory()->for($page, 'page')->create();
        }
    }
}
