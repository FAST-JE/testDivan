<?php

namespace App\Models;
class Bank
{
    public string $name;

    public array $exchangeRates = [
        'usd/rub' => 100,
        'usd/eur' => 1.1,
        'eur/rub' => 110,
        'eur/usd' => 1.1,
        'rub/usd' => 110,
        'rub/eur' => 120,
    ];



}