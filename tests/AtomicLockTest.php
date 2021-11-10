<?php

namespace Evinkuraga\Wallet\Test;

use Evinkuraga\Wallet\Services\AtomicService;

/**
 * @internal
 */
class AtomicLockTest extends TestCase
{
    public function testAtomic(): void
    {
        $atomic = app(AtomicService::class);
        $atomic->block('hello', static fn () => 'hello world');
        $atomic->block('hello', static fn () => 'hello world');
        self::assertTrue(true);
    }
}
