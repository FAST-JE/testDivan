<?php

namespace App\Models;

use App\Enums\Currency as CurrencyEnum;
use App\Exceptions\CurrencyPairDoesNotExistException;
use App\Models\Currency as CurrencyModel;

class EUR extends Currency
{
    public array $exchangeRates = [
        CurrencyEnum::RUB => 110,
        CurrencyEnum::USD => 1.1,
    ];

    public function __construct(float|CurrencyModel $value = 0)
    {
        $this->name = CurrencyEnum::EUR;
        $amount = $value;

        if ($value instanceof CurrencyModel) {
            $amount = $this->convertTo($value);
        }

        $this->value = (string)$amount;
    }

    public function setExchange(CurrencyModel $currency, float $rate): void
    {
        if (!isset($this->exchangeRates[$currency->name]))
            throw new CurrencyPairDoesNotExistException();

        $this->exchangeRates[$currency->name] = $rate;
        return;
    }

    private function convertTo(CurrencyModel $currency): string
    {
        return (string)((float)$currency->value * (float)$this->exchangeRates[$currency->name]);
    }
}