<?php

namespace App\Models;

use App\Exceptions\CurrencyPairDoesNotExistException;
use App\Models\Account as AccountModel;
use App\Models\Customer as CustomerModel;
use App\Models\Currency as CurrencyModel;
use App\Enums\Currency as CurrencyEnum;

class Bank
{

    private array $accounts = [

    ];

    public array $exchangeRates = [
        CurrencyEnum::USD . "/" . CurrencyEnum::RUB => 100,
        CurrencyEnum::USD . "/" . CurrencyEnum::EUR => 1.1,
        CurrencyEnum::EUR . "/" . CurrencyEnum::RUB => 110,
        CurrencyEnum::EUR . "/" . CurrencyEnum::USD => 1.1,
        CurrencyEnum::RUB . "/" . CurrencyEnum::USD => 0.01,
        CurrencyEnum::RUB . "/" . CurrencyEnum::EUR => 0.01,
    ];


    public function setExchange(CurrencyModel $fc, CurrencyModel $sc, float $rate): void
    {
        $key = $fc->name . '/' . $sc->name;
        if (!isset($this->exchangeRates[$key])) {
            throw new CurrencyPairDoesNotExistException();
        }

        $this->exchangeRates[$key] = $rate;
        return;
    }


    public function createAccount(CustomerModel $customer): AccountModel
    {
        $account = new AccountModel($customer);
        $this->accounts[$customer->id][] = $account;
        return $account;
    }

    public function getAllAccountUser(CustomerModel $customer): array
    {
        return $this->accounts[$customer->id];
    }
}