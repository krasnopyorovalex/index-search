<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Profiles;

use App\Domain\Crawler\ValueObjects\WebUrl;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

final class OnlyHtmlPagesProfile implements CrawlProfile
{

    public function shouldCrawl(string $url): bool
    {
        return new WebUrl($url)->isPage();
    }
}
