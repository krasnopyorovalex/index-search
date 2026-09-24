<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Repositories;

use App\Domain\Crawler\Contracts\UrlRepository;
use App\Domain\Crawler\DataTransferObjects\ClientData;
use App\Models\PageUrl;
use Illuminate\Support\Facades\DB;

final class EloquentUrlRepository implements UrlRepository
{
    public function store(ClientData $clientData): bool
    {
        $affected = PageUrl::query()->upsert(
            [
                ['client_id' => $clientData->id, 'url' => $clientData->url]
            ],
            uniqueBy: ['url_bin'],
            update: ['url' =>  DB::raw('`url`')] // для row affected = 0
        );

        return $affected > 0;
    }
}
