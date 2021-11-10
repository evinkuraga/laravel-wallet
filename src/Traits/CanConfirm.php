<?php

namespace Evinkuraga\Wallet\Traits;

use Evinkuraga\Wallet\Exceptions\BalanceIsEmpty;
use Evinkuraga\Wallet\Exceptions\ConfirmedInvalid;
use Evinkuraga\Wallet\Exceptions\InsufficientFunds;
use Evinkuraga\Wallet\Exceptions\UnconfirmedInvalid;
use Evinkuraga\Wallet\Exceptions\WalletOwnerInvalid;
use Evinkuraga\Wallet\Interfaces\Confirmable;
use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Internal\ConsistencyInterface;
use Evinkuraga\Wallet\Internal\MathInterface;
use Evinkuraga\Wallet\Models\Transaction;
use Evinkuraga\Wallet\Services\CommonService;
use Evinkuraga\Wallet\Services\DbService;
use Evinkuraga\Wallet\Services\LockService;
use Evinkuraga\Wallet\Services\WalletService;

trait CanConfirm
{
    /**
     * @throws BalanceIsEmpty
     * @throws InsufficientFunds
     * @throws ConfirmedInvalid
     * @throws WalletOwnerInvalid
     */
    public function confirm(Transaction $transaction): bool
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($transaction) {
            /** @var Confirmable|Wallet $self */
            $self = $this;

            return app(DbService::class)->transaction(static function () use ($self, $transaction) {
                $wallet = app(WalletService::class)->getWallet($self);
                if (!$wallet->refreshBalance()) {
                    return false;
                }

                if ($transaction->type === Transaction::TYPE_WITHDRAW) {
                    app(ConsistencyInterface::class)->checkPotential(
                        $wallet,
                        app(MathInterface::class)->abs($transaction->amount)
                    );
                }

                return $self->forceConfirm($transaction);
            });
        });
    }

    public function safeConfirm(Transaction $transaction): bool
    {
        try {
            return $this->confirm($transaction);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * Removal of confirmation (forced), use at your own peril and risk.
     *
     * @throws UnconfirmedInvalid
     */
    public function resetConfirm(Transaction $transaction): bool
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($transaction) {
            /** @var Wallet $self */
            $self = $this;

            return app(DbService::class)->transaction(static function () use ($self, $transaction) {
                $wallet = app(WalletService::class)->getWallet($self);
                if (!$wallet->refreshBalance()) {
                    return false;
                }

                if (!$transaction->confirmed) {
                    throw new UnconfirmedInvalid(trans('wallet::errors.unconfirmed_invalid'));
                }

                $mathService = app(MathInterface::class);
                $negativeAmount = $mathService->negative($transaction->amount);

                return $transaction->update(['confirmed' => false]) &&
                    // update balance
                    app(CommonService::class)
                        ->addBalance($wallet, $negativeAmount)
                    ;
            });
        });
    }

    public function safeResetConfirm(Transaction $transaction): bool
    {
        try {
            return $this->resetConfirm($transaction);
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * @throws ConfirmedInvalid
     * @throws WalletOwnerInvalid
     */
    public function forceConfirm(Transaction $transaction): bool
    {
        return app(LockService::class)->lock($this, __FUNCTION__, function () use ($transaction) {
            /** @var Wallet $self */
            $self = $this;

            return app(DbService::class)->transaction(static function () use ($self, $transaction) {
                $wallet = app(WalletService::class)
                    ->getWallet($self)
                ;

                if ($transaction->confirmed) {
                    throw new ConfirmedInvalid(trans('wallet::errors.confirmed_invalid'));
                }

                if ($wallet->getKey() !== $transaction->wallet_id) {
                    throw new WalletOwnerInvalid(trans('wallet::errors.owner_invalid'));
                }

                return $transaction->update(['confirmed' => true]) &&
                    // update balance
                    app(CommonService::class)
                        ->addBalance($wallet, $transaction->amount)
                    ;
            });
        });
    }
}
