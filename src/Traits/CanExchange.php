<?php

namespace Evinkuraga\Wallet\Traits;

use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Internal\ConsistencyInterface;
use Evinkuraga\Wallet\Internal\MathInterface;
use Evinkuraga\Wallet\Models\Transfer;
use Evinkuraga\Wallet\Objects\Bring;
use Evinkuraga\Wallet\Services\CommonService;
use Evinkuraga\Wallet\Services\DbService;
use Evinkuraga\Wallet\Services\ExchangeService;
use Evinkuraga\Wallet\Services\LockService;
use Evinkuraga\Wallet\Services\WalletService;

trait CanExchange
{
    /**
     * {@inheritdoc}
     */
    public function exchange(Wallet $to, $amount, ?array $meta = null): Transfer
    {
        $wallet = app(WalletService::class)->getWallet($this);

        app(ConsistencyInterface::class)->checkPotential($wallet, $amount);

        return $this->forceExchange($to, $amount, $meta);
    }

    /**
     * {@inheritdoc}
     */
    public function safeExchange(Wallet $to, $amount, ?array $meta = null): ?Transfer
    {
        try {
            return $this->exchange($to, $amount, $meta);
        } catch (\Throwable $throwable) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function forceExchange(Wallet $to, $amount, ?array $meta = null): Transfer
    {
        /** @var Wallet $from */
        $from = app(WalletService::class)->getWallet($this);

        return app(LockService::class)->lock($this, __FUNCTION__, static function () use ($from, $to, $amount, $meta) {
            return app(DbService::class)->transaction(static function () use ($from, $to, $amount, $meta) {
                $math = app(MathInterface::class);
                $rate = app(ExchangeService::class)->rate($from, $to);
                $fee = app(WalletService::class)->fee($to, $amount);

                $withdraw = app(CommonService::class)
                    ->forceWithdraw($from, $math->add($amount, $fee), $meta)
                ;

                $deposit = app(CommonService::class)
                    ->deposit($to, $math->floor($math->mul($amount, $rate, 1)), $meta)
                ;

                $transfers = app(CommonService::class)->multiBrings([
                    app(Bring::class)
                        ->setDiscount(0)
                        ->setStatus(Transfer::STATUS_EXCHANGE)
                        ->setDeposit($deposit)
                        ->setWithdraw($withdraw)
                        ->setFrom($from)
                        ->setFee($fee)
                        ->setTo($to),
                ]);

                return current($transfers);
            });
        });
    }
}
