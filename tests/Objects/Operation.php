<?php

namespace Evinkuraga\Wallet\Test\Objects;

class Operation extends \Evinkuraga\Wallet\Objects\Operation
{
    public function toArray(): array
    {
        return \array_merge(parent::toArray(), [
            'bank_method' => $this->meta['bank_method'] ?? null,
        ]);
    }
}
