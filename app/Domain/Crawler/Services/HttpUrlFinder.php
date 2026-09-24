<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Services;

use App\Domain\Crawler\Contracts\UrlFinder;
use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Domain\Crawler\Profiles\OnlyHtmlPagesProfile;
use App\Domain\Crawler\ValueObjects\WebUrl;
use App\Jobs\Crawler\StoreUrlJob;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProgress;
use Spatie\Crawler\CrawlResponse;
use Spatie\Crawler\Enums\FinishReason;
use Spatie\Crawler\Enums\ResourceType;
use Spatie\Crawler\Throttlers\AdaptiveThrottle;

readonly class HttpUrlFinder implements UrlFinder
{
    public function find(ClientData $data): void
    {
        try {
            Crawler::create($data->site)
                ->allowedMimeTypes(['text/html', 'text/plain'])
                ->crawlProfile(new OnlyHtmlPagesProfile())
                ->internalOnly()
                ->userAgent('IndexSearch/1.0')
//                ->requestTimeout(25)
                ->retry(delayInMs: 1000)
                ->depth(1)
//                ->throttle(new AdaptiveThrottle(
//                    minDelayMs: 50,
//                    maxDelayMs: 5000,
//                ))
                ->onWillCrawl(function (string $url, ?string $linkText, ?ResourceType $resourceType) {
                    // called before a URL is crawled
                })
                ->onCrawled(function (string $url, CrawlResponse $response, CrawlProgress $progress) use ($data) {
                    StoreUrlJob::dispatchIf(new WebUrl($data->url = $url)->isPage(), $data);
                })
                ->onFailed(function (string $url, RequestException $e, CrawlProgress $progress, ?string $foundOnUrl, ?string $linkText, ?ResourceType $resourceType) {
                    // called when a URL could not be crawled
                    Log::error(sprintf('Crawl URL: %s has error', $url), [
                        'exception' => $e,
                    ]);
                })
                ->onFinished(function (FinishReason $reason, CrawlProgress $progress) {

                })
                ->start();
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['exception' => $th]);
        }
    }
}
