<?php

namespace App\Models;

use App\Exceptions\CurrencyDoesNotExistInAccountException;
use App\Models\Customer as CustomerModel;
use App\Models\Currency as CurrencyModel;
use App\Models\Bank as BankModel;

class Account
{
    public string $id;
    public null|CurrencyModel $currency = null;
    public array $currencyArr = [

    ];
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
        if (gettype($this->currency) === 'NULL')
            $this->currency = $currency;

        if (!in_array($currency, $this->currencyArr)) {
            $this->currencyArr[] = $currency;
        }
    }

    public function setMainCurrency(CurrencyModel $currency): void
    {
        $this->currency = $currency;
    }

    public function getMainCurrency(): string
    {
        return $this->currency->name;
    }

    public function getAllCurrencies(): array
    {
        return array_map(fn($value): string => $value->name, $this->currencyArr);
    }

    public function deposit(CurrencyModel $currency): void {
        if (isset($this->currencyToMoneyAmount[$currency->name])) {
            $this->currencyToMoneyAmount[$currency->name] = strval(floatval($this->currencyToMoneyAmount[$currency->name]) + floatval($currency->value));
        } else {
            $this->currencyToMoneyAmount[$currency->name] = $currency->value;
        }


//        if toCur
//        ex = cur . / . cur.name
//        this.boundBank.exchange [ex]
    }

    public function getDeposit(null|CurrencyModel $currency = null): string
    {
        $getCurrency = $this->currency->name;
        if (gettype($currency) !== 'NULL') {
            $getCurrency = $currency->name;
        }
        return $this->currencyToMoneyAmount[$getCurrency];
    }

//    public function withdraw(CurrencyModel $currency): void
//    {
//        if (!isset($this->currencyToMoneyAmount[$currency->name]))
//            throw new CurrencyDoesNotExistInAccountException();
//
//    }
}