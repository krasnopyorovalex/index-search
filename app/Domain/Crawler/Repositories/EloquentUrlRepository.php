<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Repositories;

use App\Domain\Crawler\Contracts\UrlRepository;
use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Models\PageUrl;

final class EloquentUrlRepository implements UrlRepository
{
    public function store(ClientData $clientData): bool
    {
        $affected = PageUrl::query()->upsert(
            [
                ['client_id' => $clientData->id, 'url' => $clientData->url]
            ],
            uniqueBy: [], // для MySQL игнорируется, оставляем массив пустым
            update: ['url' => 'url'] // для row affected = 0
        );

        return $affected > 0;
    }
}
