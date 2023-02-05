<?php

namespace App\Models;

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

    public function __construct(CustomerModel $customer, BankModel $bank)
    {
        $this->id = uniqid();
        $this->customer = $customer;
        $this->boundBank = $bank;
    }
}