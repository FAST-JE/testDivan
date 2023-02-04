<?php

namespace App\Services;

class Bank
{
    private $account;
    private $bank;

    public function __construct(App\Services\Account $account, App\Models\Bank $bank)
    {
        $this->account = $account;
        $this->bank = $bank;
    }

    public function createAccount()
    {

    }

    public function setExchange(App\Models\Currency $fc, App\Models\Currency $sc): void
    {

    }
}