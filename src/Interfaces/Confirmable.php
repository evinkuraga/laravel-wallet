<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Interfaces;

use Evinkuraga\Wallet\Exceptions\BalanceIsEmpty;
use Evinkuraga\Wallet\Exceptions\ConfirmedInvalid;
use Evinkuraga\Wallet\Exceptions\InsufficientFunds;
use Evinkuraga\Wallet\Exceptions\WalletOwnerInvalid;
use Evinkuraga\Wallet\Models\Transaction;

interface Confirmable
{
    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws ConfirmedInvalid
     * @throws WalletOwnerInvalid
     */
    public function confirm(Transaction $transaction): bool;

    public function safeConfirm(Transaction $transaction): bool;

    /**
     * @throws ConfirmedInvalid
     */
    public function resetConfirm(Transaction $transaction): bool;

    public function safeResetConfirm(Transaction $transaction): bool;

    /**
     * @throws ConfirmedInvalid
     * @throws WalletOwnerInvalid
     */
    public function forceConfirm(Transaction $transaction): bool;
}
