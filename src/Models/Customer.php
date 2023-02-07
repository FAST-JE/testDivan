<?php

namespace App\Models;
class Customer
{
    /**
     * @var string
     */
    public readonly string $id;
    /**
     * @var string
     */
    public string $firstName;
    /**
     * @var string
     */
    public string $lastName;

    /**
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $firstName, string $lastName)
    {
        $this->id = uniqid();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}