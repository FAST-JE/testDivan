<?php

namespace App\Models;

class EUR extends Currency
{
    public function __construct(string $value = '0')
    {
        $this->name = \App\Enums\Currency::EUR;
        $this->value = $value;
    }
}