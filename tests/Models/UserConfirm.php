<?php

namespace Evinkuraga\Wallet\Test\Models;

use Evinkuraga\Wallet\Interfaces\Confirmable;
use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Traits\CanConfirm;
use Evinkuraga\Wallet\Traits\HasWallet;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class UserConfirm.
 *
 * @property string $name
 * @property string $email
 */
class UserConfirm extends Model implements Wallet, Confirmable
{
    use HasWallet;
    use CanConfirm;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email'];

    public function getTable(): string
    {
        return 'users';
    }
}
