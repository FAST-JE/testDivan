<?php

namespace App\Models;

class RUB extends Currency
{
    public string $value = '0';

    public function __construct(string $value = '0')
    {
        $this->name = \App\Enums\Currency::RUB;
        $this->value = $value;
    }

}