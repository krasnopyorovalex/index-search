<?php

declare(strict_types=1);

namespace App\Domain\Crawler\ValueObjects;

final readonly class StrBinary
{
    public function __construct(private string $string)
    {
    }

    public function asBinary(): self
    {
        return new self(hex2bin($this->string));
    }

    public function asMd5(): self
    {
        return new self(md5($this->string));
    }

    public function toStrFromBinary(): self
    {
        return new self(bin2hex($this->string));
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
