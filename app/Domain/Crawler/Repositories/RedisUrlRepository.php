<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Repositories;

use App\Domain\Crawler\Contracts\UrlRepository;
use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Domain\Crawler\ValueObjects\StrBinary;
use App\Domain\Crawler\ValueObjects\KeyByClient;

final readonly class RedisUrlRepository implements UrlRepository
{
    public function __construct(
        private UrlRepository $eloquentUrlRepository,
        private \Redis $cache
    ) {
    }

    public function store(ClientData $clientData): bool
    {
        $key = new KeyByClient($clientData);

        $binaryUrl = new StrBinary($clientData->url)->asMd5()->asBinary();

        if ($this->cache->sAdd((string)$key, (string)$binaryUrl)) {
            return $this->eloquentUrlRepository->store($clientData);
        }

        return false;
    }
}
