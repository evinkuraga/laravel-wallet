<?php

namespace Evinkuraga\Wallet\Test\Models;

use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Interfaces\WalletFloat;
use Evinkuraga\Wallet\Traits\HasWalletFloat;
use Evinkuraga\Wallet\Traits\HasWallets;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 */
class UserMulti extends Model implements Wallet, WalletFloat
{
    use HasWalletFloat;
    use HasWallets;

    public function getTable(): string
    {
        return 'users';
    }
}
