<?php

namespace App\Models;

use App\Enums\Currency;
use App\Exceptions\CurrencyPairDoesNotExistException;

class Bank
{
    public string $name;

    public array $exchangeRates = [
        Currency::USD . "/" . Currency::RUB => 100,
        Currency::USD . "/" . Currency::EUR => 1.1,
        Currency::EUR . "/" . Currency::RUB => 110,
        Currency::EUR . "/" . Currency::USD => 1.1,
        Currency::RUB . "/" . Currency::USD => 0.01,
        Currency::RUB . "/" . Currency::EUR => 0.01,
    ];


    public function setExchange(string $fc, string $sc, float $rate): void
    {
        if (!isset($this->exchangeRates[$fc . '/' . $sc]))
            throw new CurrencyPairDoesNotExistException();

        $this->exchangeRates[$fc . '/' . $sc] = $rate;
        return;
    }

//    public function getExchange(string $fc, string $sc, float $rate): void
//    {
//
//    }
}