<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\AmountWithdrawShouldNotZeroException;
use App\Exceptions\BaseCurrencyNotSet;
use App\Exceptions\CurrencyDoesNotExistInAccountException;
use App\Exceptions\CurrencyRemoveException;
use App\Exceptions\InsufficientFundsException;
use App\Models\Currency as CurrencyModel;

class Account
{
    public readonly string $id;
    private null|CurrencyModel $currency = null;
    private array $currencyToMoneyAmount = [];
    private array $currencyToClass = [];


    public function __construct()
    {
        $this->id = uniqid();
    }

    public function addCurrency(CurrencyModel $currency): void
    {
        if (!in_array($currency->getName(), $this->currencyToMoneyAmount, true)) {
            $this->currencyToMoneyAmount[$currency->getName()] = 0;
        }
        if (!in_array($currency->getName(), $this->currencyToClass, true)) {
            $this->currencyToClass[$currency->getName()] = $currency::class;
        }
    }

    public function removeCurrency(CurrencyModel $currency): void
    {
        $this->checkCurrencyExist($currency);

        if ($currency instanceof $this->currency) {
            throw new CurrencyRemoveException('Main currency cannot be removed');
        }

        $currency->setValue($this->currencyToMoneyAmount[$currency->getName()]);

        $toCurrency = new $this->currencyToClass[$this->currency->getName()]($currency);

        $this->currencyToMoneyAmount[$this->currency->getName()] += $toCurrency->getValue();
        unset($this->currencyToMoneyAmount[$currency->getName()], $this->currencyToClass[$currency->getName()]);
    }

    public function setMainCurrency(CurrencyModel $currency): void
    {
        $this->currency = $currency;
    }

    public function getMainCurrency(): string
    {
        return $this->currency->getName();
    }

    public function getAllCurrencies(): array
    {
        return array_keys($this->currencyToMoneyAmount);
    }

    public function deposit(CurrencyModel $currency): void
    {
        $currencyDeposit = $currency->getName();
        $valueDeposit = $currency->getValue();

        if (isset($this->currencyToMoneyAmount[$currencyDeposit])) {
            $this->currencyToMoneyAmount[$currencyDeposit] += $valueDeposit;
        } else {
            $this->currencyToMoneyAmount[$currencyDeposit] = $valueDeposit;
        }
    }

    public function getDeposit(null|CurrencyModel $currency = null): float
    {
        if (is_null($this->currency) && is_null($currency)) {
            throw new BaseCurrencyNotSet();
        }

        if (!is_null($currency)) {
            $this->checkCurrencyExist($currency);
            return $this->currencyToMoneyAmount[$currency->getName()];
        }

        return $this->getDepositDefault();
    }

    private function getDepositDefault(): float
    {
        $currencies = $this->getAllCurrencies();
        unset($currencies[array_flip($currencies)[$this->currency->getName()]]);
        $amount = $this->currencyToMoneyAmount[$this->currency->getName()];

        foreach ($currencies as $currencyItem) {
            $currencyObj = new $this->currencyToClass[$currencyItem]($this->currencyToMoneyAmount[$currencyItem]);
            $toCurrency = new $this->currencyToClass[$this->currency->getName()]($currencyObj);
            $amount += $toCurrency->getValue();
        }
        return $amount;
    }

    public function withdraw(CurrencyModel $currency): CurrencyModel
    {
        $this->checkCurrencyExist($currency);

        if (!$currency->getValue()) {
            throw new AmountWithdrawShouldNotZeroException();
        }

        $deposit = $this->currencyToMoneyAmount[$currency->getName()];
        $withdrawAmount = $currency->getValue();

        if ($withdrawAmount > $deposit) {
            throw new InsufficientFundsException();
        }

        $this->currencyToMoneyAmount[$currency->getName()] = $deposit - $withdrawAmount;
        return $currency;
    }

    private function checkCurrencyExist(CurrencyModel $currency): void
    {
        if (!isset($this->currencyToMoneyAmount[$currency->getName()])) {
            throw new CurrencyDoesNotExistInAccountException();
        }
    }
}