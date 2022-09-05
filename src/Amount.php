<?php

declare(strict_types=1);

namespace Gorczakw\Amount;

use JsonSerializable;
use Gorczakw\Amount\Exceptions\IncorrectAmountException;

class Amount implements JsonSerializable
{
    private int $value;

    public const QUANTITY = 100;

    public const MIN_VALUE = 0;

    public const MAX_VALUE = 4294967295;

    public const CURRENCY = 'PLN';

    public function __construct(int $value = self::MIN_VALUE)
    {
        $this->value = $value;
    }

    public function getFloat(): float
    {
        return (float)($this->value / self::QUANTITY);
    }

    /**
     * @throws IncorrectAmountException
     */
    public function setFloat(float $value): Amount
    {
        $formattedAmount = (float)number_format($value, 2, '.', '');
        if ($formattedAmount !== $value) {
            throw new IncorrectAmountException("Format of value is incorrect.");
        }

        $intValue = (int)round($value * 100);
        $this->checkValue($intValue);
        $this->value = $intValue;
        return $this;
    }

    public function getInteger(): int
    {
        return $this->value;
    }

    /**
     * @throws IncorrectAmountException
     */
    public function setInteger(int $value): Amount
    {
        $this->checkValue($value);
        $this->value = $value;
        return $this;
    }

    /**
     * @throws IncorrectAmountException
     */
    public function add(self $amount): void
    {
        $value = $this->value + $amount->getInteger();
        $this->checkValue($value);
        $this->value = $value;
    }

    /**
     * @throws IncorrectAmountException
     */
    public function subtract(self $amount): void
    {
        $value = $this->value - $amount->getInteger();
        $this->checkValue($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->getString();
    }

    /**
     * @throws IncorrectAmountException
     */
    private function checkValue(int $value): void
    {
        if ($value > self::MAX_VALUE) {
            throw new IncorrectAmountException("The final value is greater than the maximum possible.");
        }

        if ($value < self::MIN_VALUE) {
            throw new IncorrectAmountException("The final value is less than the minimum possible.");
        }
    }

    public function jsonSerialize(): string
    {
        return $this->getString();
    }

    public function getString(): string
    {
        return number_format($this->getFloat(), 2, '.', '');
    }
}
