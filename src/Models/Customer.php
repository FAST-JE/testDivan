<?php

namespace App\Models;
class Customer
{
    public readonly string $id;
    public string $firstName;
    public string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->id = uniqid();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}