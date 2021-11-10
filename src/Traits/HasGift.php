<?php

namespace Evinkuraga\Wallet\Traits;

use function app;
use Evinkuraga\Wallet\Exceptions\AmountInvalid;
use Evinkuraga\Wallet\Exceptions\BalanceIsEmpty;
use Evinkuraga\Wallet\Exceptions\InsufficientFunds;
use Evinkuraga\Wallet\Interfaces\Customer;
use Evinkuraga\Wallet\Interfaces\Product;
use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Internal\ConsistencyInterface;
use Evinkuraga\Wallet\Internal\MathInterface;
use Evinkuraga\Wallet\Models\Transfer;
use Evinkuraga\Wallet\Objects\Bring;
use Evinkuraga\Wallet\Services\CommonService;
use Evinkuraga\Wallet\Services\DbService;
use Evinkuraga\Wallet\Services\LockService;
use Evinkuraga\Wallet\Services\WalletService;
use Throwable;

/**
 * Trait HasGift.
 */
trait HasGift
{
    /**
     * Give the goods safely.
     */
    public function safeGift(Wallet $to, Product $product, bool $force = false): ?Transfer
    {
        try {
            return $this->gift($to, $product, $force);
        } catch (Throwable $throwable) {
            return null;
        }
    }

    /**
     * From this moment on, each user (wallet) can give
     * the goods to another user (wallet).
     * This functionality can be organized for gifts.
     *
     * @throws AmountInvalid
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws Throwable
     */
    public function gift(Wallet $to, Product $product, bool $force = false): Transfer
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($to, $product, $force): Transfer {
            /**
             * Who's giving? Let's call him Santa Claus.
             *
             * @var Customer $santa
             */
            $santa = $this;

            /**
             * Unfortunately,
             * I think it is wrong to make the "assemble" method public.
             * That's why I address him like this!
             */
            return app(DbService::class)->transaction(static function () use ($santa, $to, $product, $force): Transfer {
                $math = app(MathInterface::class);
                $discount = app(WalletService::class)->discount($santa, $product);
                $amount = $math->sub($product->getAmountProduct($santa), $discount);
                $meta = $product->getMetaProduct();
                $fee = app(WalletService::class)
                    ->fee($product, $amount)
                ;

                $commonService = app(CommonService::class);

                /**
                 * Santa pays taxes.
                 */
                if (!$force) {
                    app(ConsistencyInterface::class)->checkPotential($santa, $math->add($amount, $fee));
                }

                $withdraw = $commonService->forceWithdraw($santa, $math->add($amount, $fee), $meta);
                $deposit = $commonService->deposit($product, $amount, $meta);

                $from = app(WalletService::class)
                    ->getWallet($to)
                ;

                $transfers = $commonService->assemble([
                    app(Bring::class)
                        ->setStatus(Transfer::STATUS_GIFT)
                        ->setDiscount($discount)
                        ->setDeposit($deposit)
                        ->setWithdraw($withdraw)
                        ->setFrom($from)
                        ->setTo($product),
                ]);

                return current($transfers);
            });
        });
    }

    /**
     * to give force).
     *
     * @throws AmountInvalid
     * @throws Throwable
     */
    public function forceGift(Wallet $to, Product $product): Transfer
    {
        return $this->gift($to, $product, true);
    }
}
