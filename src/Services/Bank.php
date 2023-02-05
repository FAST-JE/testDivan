<?php

namespace App\Services;

use App\Models\Account as AccountModel;
use App\Models\Bank as BankModel;
use App\Models\Customer as CustomerModel;
class Bank
{
    private array $accounts = [

    ];
    private $bank;

    public function __construct(BankModel $bank)
    {
        $this->bank = $bank;
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