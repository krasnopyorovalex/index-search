<?php

declare(strict_types=1);

namespace App\Domain\Crawler\Contracts;

interface UrlSaver
{
    public function save(): bool;
}
