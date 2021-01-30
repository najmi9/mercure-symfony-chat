<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class HomeControllerTest extends TestCase
{
    public function testTest(): void
    {
        static::assertSame(12, 2);
    }
}
