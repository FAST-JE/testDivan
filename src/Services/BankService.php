<?php

namespace App\Services;

use App\Models\Account as AccountModel;
use App\Models\Bank as BankModel;
use App\Models\Currency as CurrencyModel;
use App\Models\Customer as CustomerModel;
use App\Services\AccountService;
class BankService
{
    private array $accounts = [

    ];
    private $bank;

    public function __construct(BankModel $bank)
    {
        $this->bank = $bank;
    }

    public function createAccount(CustomerModel $customer, AccountService $accountService): AccountModel
    {
        $account = new AccountModel($customer, $this->bank);
        $this->accounts[$customer->id][] = $account;
        $this->accountService = $accountService;
        return $this->accountService;
    }

    public function getAllAccountUser(CustomerModel $customer): array
    {
        return $this->accounts[$customer->id];
    }
}