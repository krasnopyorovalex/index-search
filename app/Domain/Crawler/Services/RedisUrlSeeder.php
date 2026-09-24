<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Services;

use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Domain\Crawler\ValueObjects\KeyByClient;
use Illuminate\Support\Facades\Redis;

final readonly class RedisUrlSeeder
{
    public function seed(ClientData $clientData, array $values, int $expiredAt = 1800): void
    {
        $keyByClient = new KeyByClient($clientData);

        Redis::sAddArray((string)$keyByClient, $values);

        Redis::expire((string)$keyByClient, $expiredAt);
    }
}
