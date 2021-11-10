<?php

namespace Evinkuraga\Wallet\Test\Common\Services;

use Evinkuraga\Wallet\Models\Wallet as WalletModel;
use Evinkuraga\Wallet\Services\WalletService;
use Doctrine\DBAL\Exception\InvalidArgumentException;

class WalletAdjustmentFailedService extends WalletService
{
    /**
     * @throws InvalidArgumentException
     */
    public function adjustment(WalletModel $wallet, ?array $meta = null): void
    {
        throw new InvalidArgumentException(__METHOD__);
    }
}
