<?php

declare(strict_types=1);

namespace App\Domain\Manticore\ValueObjects;

final class Table
{
    /** @var list<Row> */
    private array $rows = [];

    public function __construct(
        private(set) readonly Id $clientId
    ) {}

    public function addRow(Row $row): self
    {
        $this->rows[] = $row;
        return $this;
    }

    /**
     * @return list<array<string, int|string>>
     */
    public function toArray(): array
    {
        return array_map(fn(Row $row) => $row->toArray(), $this->rows);
    }

    public function getName(): string
    {
        return sprintf('%s_%s', config('manticore.table.prefix'), $this->clientId);
    }

    public function getFirstRow(): Row
    {
        return array_first($this->rows);
    }
}
