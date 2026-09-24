<?php

declare(strict_types=1);

namespace App\Console\Commands\Manticore;

use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Domain\Crawler\Profiles\OnlyHtmlPagesProfile;
use App\Domain\Crawler\Repositories\EloquentUrlRepository;
use App\Domain\Crawler\Repositories\RedisUrlRepository;
use App\Domain\Crawler\ValueObjects\StrBinary;
use App\Domain\Crawler\ValueObjects\KeyByClient;
use App\Models\Client;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\PageUrl;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Pforret\PfArticleExtractor\ArticleExtractor;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlResponse;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

#[Signature('manticore:test')]
#[Description('Command description')]
class Test extends Command
{
    public function handle(): int
    {
        //$client = Client::query()->firstWhere('id', 1);
        //Page::factory()->create(['client_id' => $client->id, 'title' => 'мой заголовок']);


        //Page::destroy([6,3,5]);

//        $page = Page::query()->where('id', 14)->first();
//
//        $page->fill(['title' => 'Обновил 14 страницу снова и'])->save();

//        $content = fake()->randomHtml()
//                |> (fn(string $h): string => preg_replace('/>\s+</', '><', $h))
//                |> (fn(string $h): string => str_replace(["\r\n", "\r", "\n"], ' ', $h))
//                |> trim(...);
//
//        $page = Page::factory()->create(['body_bin' => $content]);
//
//        PageContent::factory()->create(['page_id' => $page->id, 'html' => $content]);


//        $html = file_get_contents(__DIR__ . "/body_gzipped_9.html");
//
//        $ungzipped = gzdecode($html);
//
//        file_put_contents(__DIR__ . "/body_gzipped_unzipped.html", $ungzipped);

//        for($i = 1; $i <= 9; $i++) {
//            $htmlGzipped = gzencode($html, $i);
//
//            file_put_contents(__DIR__ . "/body_gzipped_{$i}.html", $htmlGzipped);
//        }

//        $html = file_get_contents(__DIR__ . "/raw_body_1.html");
//        $crawler = new Crawler($html);
//
//        $this->info(
//            $crawler->filter('meta[name="description"]')->eq(0)->attr('content')
//        );


//        $i = 1;
//
//
//        $pageContent = PageContent::query()->where('page_id', 1)->firstOrFail();

        //echo $pageContent->body_gzipped, PHP_EOL;


//        $sitemap = SitemapGenerator::create('https://fabrikabani-krym.ru')
//            ->configureCrawler(function (Crawler $crawler) {
//                $crawler->crawlProfile(new OnlyHtmlPagesProfile())
//                    ->depth(2)
//                    ->internalOnly();
//            })
//            ->getSitemap();
//
//        $lines = array_map(fn($tag) => $tag->url, $sitemap->getTags());
//
//        Redis::sAdd('client:1', implode(PHP_EOL, $lines));

//        $lines = ['https://fabrikabani-krym.ru', 'https://fabrikabani-krym.ru/sitemap', 'https://fabrikabani-krym.ru/contacts'];
//
//        //Redis::sAdd('client:1', implode(PHP_EOL, $lines));
//
//        array_map(function (string $line) {
//            Redis::rPush('client:2', $line);
//        }, $lines);

//        echo Redis::sismember('client:1');

        //file_put_contents(public_path('sitemap.txt'), implode(PHP_EOL, $lines));


//        $urls = Crawler::create('https://fabrikabani-krym.ru')
//            ->internalOnly()
//            //->depth(20)
//            ->foundUrls();
//
//
//        $i = 1;
//        foreach ($urls as $url) {
//            file_put_contents(__DIR__ . '/urls.txt', sprintf('%d. Глубина: %s, Адрес: %s%s', $i++, $url->depth, $url->url, PHP_EOL), FILE_APPEND);
//        }

//        try {
//            $ip = '45.130.41.42';
//            $host = 'fabrikabani-krym.ru';
//            $url = "https://{$host}/some-page";
//
//            $response = Http::withOptions([
//                'curl' => [
//                    CURLOPT_RESOLVE => ["{$host}:443:{$ip}"],
//                ],
//            ])->get($url);
//
//            echo $response->body(), PHP_EOL;
//        } catch (\Throwable $exception) {
//            var_dump($exception->getMessage());
//        }

//        $html = file_get_contents('https://krasber.ru/contacts');
//        $article = ArticleExtractor::getArticle($html);
//
//        // $article->content — это уже очищенный от меню/сайдбаров текст
//        echo $article->title, PHP_EOL;    // Заголовок статьи
//        echo $article->content, PHP_EOL;  // Основной контент


//        try {
//            $new = new \App\Models\PageUrl();
//            $new->page_id = 3;
//            $new->url = 'https://fabrikabani-krym.ru';
//            $new->save();
//        } catch (QueryException $exception) {
//            $this->info($exception->getMessage());
//        }


        $clientData = ClientData::fromEloquent(Client::query()->where('id', 1)->firstOrFail());

        $urls = PageUrl::query()->select(['url_bin', 'client_id'])->where('client_id', $clientData->id)->get();

        $key = new KeyByClient($clientData);

        Redis::client()->sAddArray(
            (string)$key,
            $urls->pluck('url_bin')->map(fn($url) => (string)new StrBinary($url)->asBinary())->toArray()
        );
        Redis::client()->expire((string)$key, 1000);

//        $binaryUrl = new StrBinary('https://fabrikabani-krym.ru/personal-data-54547')->asMd5()->asBinary();
//
//        $result = Redis::client()->sAdd((string)$key, (string)$binaryUrl);
//
//        $this->info(sprintf('Находится в множестве? Ответ: %s', $result ? 'Нет' : 'Да'));

        return 0;
    }
}
