<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Internal;

use Evinkuraga\Wallet\Exceptions\AmountInvalid;
use Evinkuraga\Wallet\Exceptions\BalanceIsEmpty;
use Evinkuraga\Wallet\Exceptions\InsufficientFunds;
use Evinkuraga\Wallet\Interfaces\Wallet;

interface ConsistencyInterface
{
    /**
     * @param float|int|string $amount
     *
     * @throws AmountInvalid
     */
    public function checkPositive($amount): void;

    /**
     * @param float|int|string $amount
     *
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     */
    public function checkPotential(Wallet $wallet, $amount, bool $allowZero = false): void;
}
