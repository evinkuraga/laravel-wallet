<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Interfaces;

use Evinkuraga\Wallet\Exceptions\AmountInvalid;
use Evinkuraga\Wallet\Exceptions\BalanceIsEmpty;
use Evinkuraga\Wallet\Exceptions\InsufficientFunds;
use Evinkuraga\Wallet\Models\Transaction;
use Evinkuraga\Wallet\Models\Transfer;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Wallet
{
    /**
     * @param int|string $amount
     *
     * @throws AmountInvalid
     */
    public function deposit($amount, ?array $meta = null, bool $confirmed = true): Transaction;

    /**
     * @param int|string $amount
     *
     * @throws AmountInvalid
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     */
    public function withdraw($amount, ?array $meta = null, bool $confirmed = true): Transaction;

    /**
     * @param int|string $amount
     *
     * @throws AmountInvalid
     */
    public function forceWithdraw($amount, ?array $meta = null, bool $confirmed = true): Transaction;

    /**
     * @param int|string $amount
     *
     * @throws AmountInvalid
     */
    public function transfer(self $wallet, $amount, ?array $meta = null): Transfer;

    /**
     * @param int|string $amount
     *
     * @throws AmountInvalid
     */
    public function safeTransfer(self $wallet, $amount, ?array $meta = null): ?Transfer;

    /**
     * @param int|string $amount
     *
     * @throws AmountInvalid
     */
    public function forceTransfer(self $wallet, $amount, ?array $meta = null): Transfer;

    /**
     * @param int|string $amount
     */
    public function canWithdraw($amount, bool $allowZero = false): bool;

    /**
     * @return float|int
     */
    public function getBalanceAttribute();

    public function transactions(): MorphMany;
}
