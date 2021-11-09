<?php

namespace Evinkuraga\Wallet\Test\Models;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallet;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 */
class User extends Model implements Wallet
{
    use HasWallet;

    /**
     * @var array
     */
    protected $fillable = ['name', 'email'];
}
