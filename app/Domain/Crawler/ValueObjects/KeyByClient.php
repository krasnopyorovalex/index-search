<?php

declare(strict_types=1);

namespace App\Domain\Crawler\ValueObjects;

use App\Domain\Crawler\DataTransferObjects\ClientData;

final readonly class KeyByClient
{
    public function __construct(private ClientData $clientData)
    {
    }

    public function __toString(): string
    {
        return sprintf('client:urls:%s', $this->clientData->id);
    }
}
