<?php

namespace Evinkuraga\Wallet\Test\Models;

use Evinkuraga\Wallet\Traits\HasWallets;

class ItemWallet extends Item
{
    use HasWallets;

    public function getTable(): string
    {
        return 'items';
    }
}
