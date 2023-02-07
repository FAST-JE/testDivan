<?php

namespace App\Models;

use App\Exceptions\CurrencyPairDoesNotExistException;

class Currency
{
    /**
     * @var string
     */
    public string $name = '';
    /**
     * @var string
     */
    public string $value = '';
    /**
     * @var array
     */
    public array $exchangeRates = [];

    /**
     * @param string $value
     * @return void
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
        return;
    }

    /**
     * @param Currency $currency
     * @param float $rate
     * @return void
     * @throws CurrencyPairDoesNotExistException
     */
    public function setExchange(Currency $currency, float $rate): void
    {
        if (!isset($this->exchangeRates[$currency->name]))
            throw new CurrencyPairDoesNotExistException();

        $this->exchangeRates[$currency->name] = $rate;
        return;
    }

    /**
     * @param Currency $currency
     * @return string
     */
    protected function convertTo(Currency $currency): string
    {
        return (string)((float)$currency->value * (float)$this->exchangeRates[$currency->name]);
    }
}