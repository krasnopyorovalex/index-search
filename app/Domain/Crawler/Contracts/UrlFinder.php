<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Contracts;

use App\Domain\Crawler\DataTransferObjects\ClientData;

interface UrlFinder
{
    public function find(ClientData $data): void;
}
