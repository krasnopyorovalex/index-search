<?php

declare(strict_types=1);

namespace Tests\Unit\Manticore;

use App\Domain\Manticore\ValueObjects\Id;
use App\Domain\Manticore\ValueObjects\Row;
use App\Domain\Manticore\ValueObjects\Table;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('manticore')]
class TableTest extends TestCase
{
    #[DataProvider('additionProvider')]
    public function test_create_table_correct(array $expected, array $in): void
    {
        $table = new Table(new Id(25));
        $table->addRow(Row::fromArray($in));

        $table->toArray()
            |> array_first(...)
            |> (fn($x) => $this->assertEquals($expected, $x));
    }

    public function test_create_empty_table_is_correct_work(): void
    {
        $table = new Table(new Id(1));

        $this->assertEmpty($table->toArray());
    }

    public static function additionProvider(): array
    {
        $createdAt = now()->getTimestamp();
        $updatedAt = now()->getTimestamp();

        return [
            [
                [
                    'id' => 1,
                    'title' => 'Привет',
                    'content' => 'Как дела привет',
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ],
                [
                    'id' => 1,
                    'title' => 'Привет',
                    'content' => 'Как дела привет',
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]
            ]
        ];
    }
}
