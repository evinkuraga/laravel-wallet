<?php

namespace Evinkuraga\Wallet\Test;

use Evinkuraga\Wallet\Services\BookkeeperService;
use Evinkuraga\Wallet\Test\Factories\BuyerFactory;
use Evinkuraga\Wallet\Test\Models\Buyer;

/**
 * @internal
 */
class BookkeeperTest extends TestCase
{
    public function testSync(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();

        $booker = app(BookkeeperService::class);
        self::assertTrue($booker->sync($buyer->wallet, 42));
        self::assertSame('42', $booker->amount($buyer->wallet));
    }

    public function testAmount(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        $buyer->deposit(42);
        $buyer->withdraw(11);
        $buyer->deposit(1);

        $booker = app(BookkeeperService::class);
        self::assertSame('32', $booker->amount($buyer->wallet));
    }

    public function testIncrease(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();

        $booker = app(BookkeeperService::class);
        self::assertSame('5', $booker->increase($buyer->wallet, 5));
        self::assertTrue($booker->missing($buyer->wallet));
        self::assertSame('0', $booker->amount($buyer->wallet));
    }
}
