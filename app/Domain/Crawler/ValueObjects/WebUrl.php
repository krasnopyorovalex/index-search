<?php

declare(strict_types=1);

namespace App\Domain\Crawler\ValueObjects;

final class WebUrl
{
    private const array EXCLUDED_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf',
        'zip', 'rar', 'css', 'js', 'xml', 'txt', 'csv',
    ];

    public function __construct(private(set) readonly string $url)
    {
    }

    public function isPage(): bool
    {
        $path = parse_url($this->url, PHP_URL_PATH) ?? '';
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return !in_array($extension, self::EXCLUDED_EXTENSIONS, true);
    }

    public function asUniqueInDb(): false|string
    {
        return hex2bin(md5($this->url));
    }
}
