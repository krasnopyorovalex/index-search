<?php

declare(strict_types=1);

namespace App\Domain\Manticore\ValueObjects;

final readonly class Id
{
    private int $id;

    public function __construct(int $id)
    {
        if ($id < 1) {
            throw new \InvalidArgumentException('$id must be a positive integer');
        }

        $this->id = $id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
