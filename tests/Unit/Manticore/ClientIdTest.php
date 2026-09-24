<?php

namespace Tests\Unit\Manticore;

use App\Domain\Manticore\ValueObjects\Id;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('manticore')]
class ClientIdTest extends TestCase
{
    public function test_client_id_as_string_and_correct(): void
    {
        $clientId = new Id(2);

        $this->assertIsString((string)$clientId);
        $this->assertSame('2', (string)$clientId);
    }

    public function test_client_id_is_not_negative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $clientId = new Id(-2);

        $this->assertIsString((string)$clientId);
    }
}
