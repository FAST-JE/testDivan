<?php

namespace App\Models;

use App\Enums\Currency as CurrencyEnum;
use App\Exceptions\CurrencyPairDoesNotExistException;
use App\Models\Currency as CurrencyModel;
class RUB extends Currency
{
    public function __construct(float|CurrencyModel $value = 0)
    {
        $this->exchangeRates = [
            CurrencyEnum::EUR => 110,
            CurrencyEnum::USD => 100,
        ];

        $this->name = CurrencyEnum::RUB;
        $amount = $value;

        if ($value instanceof CurrencyModel) {
            $amount = $this->convertTo($value);
        }

        $this->value = (string)$amount;
    }
}