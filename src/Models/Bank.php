<?php

declare(strict_types=1);

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