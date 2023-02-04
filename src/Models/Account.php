<?php

namespace App\Models;

class Account
{
    public int $id;
    public int $currency;
    public Customer $customer;

}