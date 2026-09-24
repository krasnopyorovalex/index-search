<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Profiles;

use App\Domain\Crawler\ValueObjects\WebUrl;
use Spatie\Crawler\CrawlProfiles\CrawlInternalUrls;

final class OnlyHtmlPagesProfile extends CrawlInternalUrls
{
    public function shouldCrawl(string $url): bool
    {
        return parent::shouldCrawl($url) && new WebUrl($url)->isPage();
    }
}
