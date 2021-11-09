<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Internal;

use Bavix\Wallet\Internal\Exceptions\LockProviderNotFoundException;

interface LockInterface
{
    /**
     * @throws LockProviderNotFoundException
     *
     * @return mixed
     */
    public function block(string $key, callable $callback);
}
