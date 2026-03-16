<?php

declare(strict_types=1);

namespace Rearm\Tests;

use PHPUnit\Framework\TestCase;
use Rearm\Rearm;

class RearmTest extends TestCase
{
    public function testRetriesUntilSuccess(): void
    {
        $count = 0;

        $result = Rearm::times(3)->run(function () use (&$count) {

            $count++;

            if ($count < 2) {
                throw new \RuntimeException("Fail");
            }

            return "ok";
        });

        $this->assertEquals("ok", $result);
        $this->assertEquals(2, $count);
    }

    public function testThrowsExceptionAfterMaxAttempts(): void
    {
        $this->expectException(\RuntimeException::class);

        Rearm::times(3)->run(function () {
            throw new \RuntimeException("Always fails");
        });
    }
}