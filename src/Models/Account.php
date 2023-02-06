<?php

namespace App\Models;

use App\Exceptions\AmountWithdrawShouldNotZeroException;
use App\Exceptions\CurrencyDoesNotExistInAccountException;
use App\Exceptions\CurrencyExistInAccountException;
use App\Exceptions\CurrencyRemoveException;
use App\Exceptions\InsufficientFundsException;
use App\Models\Customer as CustomerModel;
use App\Models\Currency as CurrencyModel;
use App\Models\Bank as BankModel;

class Account
{
    public string $id;
    public null|CurrencyModel $currency = null;
    public array $currencyToMoneyAmount = [

    ];
    public CustomerModel $customer;
    public BankModel $boundBank;

    public function __construct(CustomerModel $customer)
    {
        $this->id = uniqid();
        $this->customer = $customer;
    }

    public function addCurrency(CurrencyModel $currency): void
    {
        if (in_array($currency, $this->currencyToMoneyAmount, false)) {
            throw new CurrencyExistInAccountException();
        }

        $this->currencyToMoneyAmount[$currency->name] = '0';
        return;
    }

    public function removeCurrency(CurrencyModel $currency): void
    {
        $this->checkCurrencyExist($currency);

        if ($currency instanceof $this->currency) {
            throw new CurrencyRemoveException('Main currency cannot be removed');
        }

        $classFirstCurrency = get_class($currency);
        $value = new $classFirstCurrency($this->currencyToMoneyAmount[$currency->name]);
        $classSecondCurrency = get_class($this->currency);
        $toCurrency = $classSecondCurrency($value);
        $this->currencyToMoneyAmount[$this->currency->name] = (string)((float)$this->currencyToMoneyAmount[$this->currency->name] + (float)$toCurrency->value);
        array_splice($this->currencyToMoneyAmount, $currency->name, 1);
        return;
    }

    public function setMainCurrency(CurrencyModel $currency): void
    {
        $this->currency = $currency;
        return;
    }

    public function getMainCurrency(): string
    {
        return $this->currency->name;
    }

    public function getAllCurrencies(): array
    {
        return array_keys($this->currencyToMoneyAmount);
    }

    public function deposit(CurrencyModel $currency): void
    {
        $currencyDeposit = $currency->name;
        $valueDeposit = $currency->value;

        if (isset($this->currencyToMoneyAmount[$currencyDeposit])) {
            $this->currencyToMoneyAmount[$currencyDeposit] = (string)((float)$this->currencyToMoneyAmount[$currencyDeposit] + (float)$valueDeposit);
        } else {
            $this->currencyToMoneyAmount[$currencyDeposit] = $valueDeposit;
        }

        return;
    }

    public function getDeposit(null|CurrencyModel $currency = null): string
    {
        $getCurrency = $this->currency;
        if (!is_null($currency)) {
            $getCurrency = $currency;
        }
        $this->checkCurrencyExist($getCurrency);

        return $this->currencyToMoneyAmount[$getCurrency->name];
    }

    public function withdraw(CurrencyModel $currency): void
    {
        $this->checkCurrencyExist($currency);

        if ($currency->value === '0') {
            throw new AmountWithdrawShouldNotZeroException();
        }

        $deposit = (float)$this->currencyToMoneyAmount[$currency->name];
        $withdrawAmount = (float)$currency->value;

        if ($withdrawAmount > $deposit) {
            throw new InsufficientFundsException();
        }

        $this->currencyToMoneyAmount[$currency->name] = (string)((float)$deposit - (float)$withdrawAmount);
    }

    private function checkCurrencyExist(CurrencyModel $currency): void
    {
        if (!isset($this->currencyToMoneyAmount[$currency->name])) {
            throw new CurrencyDoesNotExistInAccountException();
        }

        return;
    }
}