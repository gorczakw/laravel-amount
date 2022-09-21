<?php

declare(strict_types=1);

namespace Gorczakw\LaravelAmount;

use Gorczakw\Amount\Amount;
use Gorczakw\Amount\Exceptions\IncorrectAmountException;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AmountCast implements CastsAttributes
{
    /**
     * @throws IncorrectAmountException
     */
    public function get($model, string $key, $value, array $attributes): Amount
    {
        return (new Amount())->setInteger((int)$value);
    }

    /**
     * @throws IncorrectAmountException
     */
    public function set($model, string $key, $value, array $attributes): int
    {
        if (!$value instanceof Amount) {
            throw new IncorrectAmountException("Format of value is incorrect.");
        }

        return $value->getInteger();
    }
}
