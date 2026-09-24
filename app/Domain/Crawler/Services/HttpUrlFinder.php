<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Services;

use App\Domain\Crawler\Contracts\UrlFinder;
use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Domain\Crawler\Profiles\OnlyHtmlPagesProfile;
use App\Jobs\Crawler\StoreUrlJob;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Log;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlProgress;
use Spatie\Crawler\CrawlResponse;
use Spatie\Crawler\Enums\FinishReason;
use Spatie\Crawler\Enums\ResourceType;
use Spatie\Crawler\Throttlers\AdaptiveThrottle;

final readonly class HttpUrlFinder implements UrlFinder
{
    public function find(ClientData $data): void
    {
        try {
            Crawler::create($data->host, [
                    RequestOptions::CURL => [
                        CURLOPT_RESOLVE => ["{$data->host}:443:{$data->ip}"],
                    ],
                ])
                ->allowedMimeTypes(['text/html', 'text/plain'])
                ->crawlProfile(new OnlyHtmlPagesProfile($data->host))
                ->depth(maximumDepth: 3)
                ->userAgent('IndexSearch/1.0')
                ->requestTimeout(requestTimeoutInSeconds: 60)
                ->retry(times: 3, delayInMs: 1000)
                ->throttle(new AdaptiveThrottle(
                    minDelayMs: 50,
                    maxDelayMs: 4000,
                ))
                ->onCrawled(function (string $url, CrawlResponse $response, CrawlProgress $progress) use ($data) {
                    StoreUrlJob::dispatchIf($data->url = $url, $data);
                })
                ->onFailed(function (string $url, \Throwable $e, CrawlProgress $progress, ?string $foundOnUrl, ?string $linkText, ?ResourceType $resourceType) {
                    Log::error(sprintf('Crawl URL: %s has error', $url), [
                        'foundOnUrl' => $foundOnUrl,
                        'linkText' => $linkText,
                        'message' => $e->getMessage()
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
