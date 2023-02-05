<?php

namespace App\Models;

class USD extends Currency
{
    public string $value = '0';
    public function __construct(string $value = '0')
    {
        $this->name = \App\Enums\Currency::USD;
        $this->value = $value;
    }

}