<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Contracts;

use App\Domain\Crawler\DataTransferObjects\ClientData;

interface UrlRepository
{
    /**
     * @param ClientData $clientData
     * @return bool
     */
    public function store(ClientData $clientData): bool;
}
