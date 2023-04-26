<?php

declare(strict_types=1);

namespace App\Models;
class Customer
{
    public readonly string $id;
    private string $firstName;
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->id = uniqid();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}