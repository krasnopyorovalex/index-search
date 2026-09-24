<?php

declare(strict_types=1);

namespace App\Domain\Manticore\DataTransferObjects;

final class RowData
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public int $createdAt,
        public int $updatedAt
    ) {}
}
