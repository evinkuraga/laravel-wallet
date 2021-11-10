<?php

namespace Evinkuraga\Wallet\Test;

use Evinkuraga\Wallet\Objects\Operation;
use Evinkuraga\Wallet\Test\Common\Models\Transaction;
use Evinkuraga\Wallet\Test\Common\Models\TransactionMoney;
use Evinkuraga\Wallet\Test\Factories\BuyerFactory;
use Evinkuraga\Wallet\Test\Models\Buyer;

/**
 * @internal
 */
class WalletExtensionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->app->bind(Operation::class, Objects\Operation::class);
    }

    public function testCustomAttribute(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        self::assertFalse($buyer->relationLoaded('wallet'));
        $transaction = $buyer->deposit(1000, ['bank_method' => 'VietComBank']);

        self::assertEquals($transaction->amount, $buyer->balance);
        self::assertInstanceOf(Transaction::class, $transaction);
        self::assertEquals('VietComBank', $transaction->bank_method);
    }

    public function testTransactionMoneyAttribute(): void
    {
        $this->app['config']->set('wallet.transaction.model', TransactionMoney::class);

        /**
         * @var Buyer            $buyer
         * @var TransactionMoney $transaction
         */
        $buyer = BuyerFactory::new()->create();
        self::assertFalse($buyer->relationLoaded('wallet'));
        $transaction = $buyer->deposit(1000, ['currency' => 'EUR']);

        self::assertEquals($transaction->amount, $buyer->balance);
        self::assertInstanceOf(TransactionMoney::class, $transaction);
        self::assertEquals(1000, $transaction->currency->getAmount());
        self::assertEquals('EUR', $transaction->currency->getCurrency()->getCode());
    }

    public function testNoCustomAttribute(): void
    {
        /** @var Buyer $buyer */
        $buyer = BuyerFactory::new()->create();
        self::assertFalse($buyer->relationLoaded('wallet'));
        $transaction = $buyer->deposit(1000);

        self::assertEquals($transaction->amount, $buyer->balance);
        self::assertInstanceOf(Transaction::class, $transaction);
        self::assertNull($transaction->bank_method);
    }
}
