<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Currency as CurrencyEnum;
use App\Exceptions\CurrencyPairDoesNotExistException;
use App\Models\Currency as CurrencyModel;

class USD extends Currency
{
    public function __construct(float|CurrencyModel $value = 0)
    {
        $this->exchangeRates = [
            CurrencyEnum::RUB => 0.01,
            CurrencyEnum::EUR => 1.1,
        ];

        $this->name = CurrencyEnum::USD;
        $amount = $value;

        if ($value instanceof CurrencyModel) {
            $amount = $this->convertTo($value);
        }

        $this->setValue($amount);
    }
}