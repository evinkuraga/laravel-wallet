<?php

namespace Evinkuraga\Wallet\Test\Models;

use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Interfaces\WalletFloat;
use Evinkuraga\Wallet\Traits\HasWalletFloat;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class UserFloat.
 *
 * @property string $name
 * @property string $email
 */
class UserFloat extends Model implements Wallet, WalletFloat
{
    use HasWalletFloat;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email'];

    public function getTable(): string
    {
        return 'users';
    }
}
