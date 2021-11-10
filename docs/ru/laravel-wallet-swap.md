## Laravel Wallet Swap

## Composer

Рекомендуем установку используя [Composer](https://getcomposer.org/).

В корне вашего проекта запустите:

```bash
composer req bavix/laravel-wallet-swap
```

### Пользователь
Для работы библиотеки нужны мульти-кошельки, 
поскольку транзакции будут между кошельками одного пользователя.

```php
use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Traits\HasWallets;
use Evinkuraga\Wallet\Traits\HasWallet;

class User extends Model implements Wallet
{
    use HasWallet, HasWallets;
}
```

### Простой пример
Находим кошельки пользователя и переводим с одного на другой.

```php
$usd = $user->getWallet('usd');
$rub = $user->getWallet('rub');

$usd->balance; // 200
$rub->balance; // 0

$usd->exchange($rub, 10);
$usd->balance; // 190
$rub->balance; // 622
```

Это работает.
