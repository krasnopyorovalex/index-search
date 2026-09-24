<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Services;

use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Domain\Crawler\ValueObjects\KeyByClient;
use Illuminate\Support\Facades\Redis;

final readonly class RedisUrlSeeder
{
    public function seed(ClientData $clientData, array $values, int $expiredAt = 86400): void
    {
        $key = (string)new KeyByClient($clientData);

        Redis::del($key);

        if (count($values)) {
            Redis::sAddArray($key, $values);
            Redis::expire($key, $expiredAt);
        }
    }
}
