<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Profiles;

use Spatie\Crawler\CrawlProfiles\CrawlProfile;

final class OnlyHtmlPagesProfile implements CrawlProfile
{
    private const array EXCLUDED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf',
        'zip', 'rar', 'css', 'js', 'xml', 'txt', 'csv',
    ];

    public function shouldCrawl(string $url): bool
    {
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));

        dd($extension);

        return !in_array($extension, self::EXCLUDED_EXTENSIONS, true);
    }
}
