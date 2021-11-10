<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Services;

use Evinkuraga\Wallet\Internal\BookkeeperInterface;
use Evinkuraga\Wallet\Internal\Exceptions\RecordNotFoundException;
use Evinkuraga\Wallet\Internal\LockInterface;
use Evinkuraga\Wallet\Internal\StorageInterface;
use Evinkuraga\Wallet\Models\Wallet;

class BookkeeperService implements BookkeeperInterface
{
    private StorageInterface $storage;

    private LockInterface $lock;

    public function __construct(
        StorageInterface $storage,
        LockInterface $lock
    ) {
        $this->storage = $storage;
        $this->lock = $lock;
    }

    public function missing(Wallet $wallet): bool
    {
        return $this->storage->missing($this->getKey($wallet));
    }

    public function amount(Wallet $wallet): string
    {
        try {
            return $this->storage->get($this->getKey($wallet));
        } catch (RecordNotFoundException $recordNotFoundException) {
            $this->lock->block(
                $this->getKey($wallet),
                fn () => $this->storage->sync(
                    $this->getKey($wallet),
                    $wallet->getOriginalBalance(),
                ),
            );
        }

        return $this->storage->get($this->getKey($wallet));
    }

    public function sync(Wallet $wallet, $value): bool
    {
        return $this->storage->sync($this->getKey($wallet), $value);
    }

    public function increase(Wallet $wallet, $value): string
    {
        try {
            return $this->storage->increase($this->getKey($wallet), $value);
        } catch (RecordNotFoundException $recordNotFoundException) {
            $this->amount($wallet);
        }

        return $this->storage->increase($this->getKey($wallet), $value);
    }

    private function getKey(Wallet $wallet): string
    {
        return __CLASS__.'::'.$wallet->getKey();
    }
}
