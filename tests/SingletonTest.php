<?php

namespace Evinkuraga\Wallet\Test;

use Evinkuraga\Wallet\Interfaces\Mathable;
use Evinkuraga\Wallet\Interfaces\Rateable;
use Evinkuraga\Wallet\Interfaces\Storable;
use Evinkuraga\Wallet\Internal\MathInterface;
use Evinkuraga\Wallet\Objects\Bring;
use Evinkuraga\Wallet\Objects\Cart;
use Evinkuraga\Wallet\Objects\EmptyLock;
use Evinkuraga\Wallet\Objects\Operation;
use Evinkuraga\Wallet\Services\CommonService;
use Evinkuraga\Wallet\Services\DbService;
use Evinkuraga\Wallet\Services\ExchangeService;
use Evinkuraga\Wallet\Services\LockService;
use Evinkuraga\Wallet\Services\WalletService;
use Evinkuraga\Wallet\Test\Common\Models\Transaction;
use Evinkuraga\Wallet\Test\Common\Models\Transfer;
use Evinkuraga\Wallet\Test\Common\Models\Wallet;

/**
 * @internal
 */
class SingletonTest extends TestCase
{
    public function testBring(): void
    {
        self::assertNotEquals($this->getRefId(Bring::class), $this->getRefId(Bring::class));
    }

    public function testCart(): void
    {
        self::assertNotEquals($this->getRefId(Cart::class), $this->getRefId(Cart::class));
    }

    public function testEmptyLock(): void
    {
        self::assertNotEquals($this->getRefId(EmptyLock::class), $this->getRefId(EmptyLock::class));
    }

    public function testOperation(): void
    {
        self::assertNotEquals($this->getRefId(Operation::class), $this->getRefId(Operation::class));
    }

    public function testRateable(): void
    {
        self::assertEquals($this->getRefId(Rateable::class), $this->getRefId(Rateable::class));
    }

    public function testStorable(): void
    {
        self::assertEquals($this->getRefId(Storable::class), $this->getRefId(Storable::class));
    }

    public function testMathable(): void
    {
        self::assertEquals($this->getRefId(Mathable::class), $this->getRefId(Mathable::class));
    }

    public function testMathInterface(): void
    {
        self::assertEquals($this->getRefId(MathInterface::class), $this->getRefId(MathInterface::class));
    }

    public function testTransaction(): void
    {
        self::assertNotEquals($this->getRefId(Transaction::class), $this->getRefId(Transaction::class));
    }

    public function testTransfer(): void
    {
        self::assertNotEquals($this->getRefId(Transfer::class), $this->getRefId(Transfer::class));
    }

    public function testWallet(): void
    {
        self::assertNotEquals($this->getRefId(Wallet::class), $this->getRefId(Wallet::class));
    }

    public function testExchangeService(): void
    {
        self::assertEquals($this->getRefId(ExchangeService::class), $this->getRefId(ExchangeService::class));
    }

    public function testCommonService(): void
    {
        self::assertEquals($this->getRefId(CommonService::class), $this->getRefId(CommonService::class));
    }

    public function testWalletService(): void
    {
        self::assertEquals($this->getRefId(WalletService::class), $this->getRefId(WalletService::class));
    }

    public function testDbService(): void
    {
        self::assertEquals($this->getRefId(DbService::class), $this->getRefId(DbService::class));
    }

    public function testLockService(): void
    {
        self::assertEquals($this->getRefId(LockService::class), $this->getRefId(LockService::class));
    }

    protected function getRefId(string $object): string
    {
        return spl_object_hash(app($object));
    }
}
