<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Services;

use Evinkuraga\Wallet\Exceptions\AmountInvalid;
use Evinkuraga\Wallet\Exceptions\BalanceIsEmpty;
use Evinkuraga\Wallet\Exceptions\InsufficientFunds;
use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Internal\ConsistencyInterface;
use Evinkuraga\Wallet\Internal\MathInterface;
use Evinkuraga\Wallet\Traits\HasWallet;

class ConsistencyService implements ConsistencyInterface
{
    private MathInterface $math;

    public function __construct(MathInterface $math)
    {
        $this->math = $math;
    }

    /**
     * @param float|int|string $amount
     *
     * @throws AmountInvalid
     */
    public function checkPositive($amount): void
    {
        if ($this->math->compare($amount, 0) === -1) {
            throw new AmountInvalid(trans('wallet::errors.price_positive'));
        }
    }

    /**
     * @param float|int|string $amount
     *
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     */
    public function checkPotential(Wallet $wallet, $amount, bool $allowZero = false): void
    {
        /**
         * @var HasWallet $wallet
         */
        if ($amount && !$wallet->balance) {
            throw new BalanceIsEmpty(trans('wallet::errors.wallet_empty'));
        }

        if (!$wallet->canWithdraw($amount, $allowZero)) {
            throw new InsufficientFunds(trans('wallet::errors.insufficient_funds'));
        }
    }
}
