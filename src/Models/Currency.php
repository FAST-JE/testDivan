<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\CurrencyPairDoesNotExistException;

class Currency
{
    public string $name;
    public float $value;
    protected array $exchangeRates = [];

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setExchange(Currency $currency, float $rate): void
    {
        if (!isset($this->exchangeRates[$currency->getName()])) {
            throw new CurrencyPairDoesNotExistException();
        }

        $this->exchangeRates[$currency->getName()] = $rate;
    }

    protected function convertTo(Currency $currency): float
    {
        return $currency->getValue() * $this->exchangeRates[$currency->getName()];
    }
}