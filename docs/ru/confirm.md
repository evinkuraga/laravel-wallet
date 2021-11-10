## User Model

Добавьте `CanConfirm` trait и `Confirmable` interface в модель User.

```php
use Evinkuraga\Wallet\Interfaces\Confirmable;
use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Traits\CanConfirm;
use Evinkuraga\Wallet\Traits\HasWallet;

class UserConfirm extends Model implements Wallet, Confirmable
{
    use HasWallet, CanConfirm;
}
```

### Example:

Иногда, необходимо подтвердить операцию и пересчитать баланс.
Теперь это доступно в библиотеке из коробки. Вот пример:

```php
$user->balance; // 0
$transaction = $user->deposit(100, null, false); // не подтверждена
$transaction->confirmed; // bool(false)
$user->balance; // 0

$user->confirm($transaction); // bool(true)
$transaction->confirmed; // bool(true)

$user->balance; // 100 
```

Это работает!
