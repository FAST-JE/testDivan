<?php

namespace App\Models;

use App\Exceptions\AmountWithdrawShouldNotZeroException;
use App\Exceptions\BaseCurrencyNotSet;
use App\Exceptions\CurrencyDoesNotExistInAccountException;
use App\Exceptions\CurrencyRemoveException;
use App\Exceptions\InsufficientFundsException;
use App\Models\Customer as CustomerModel;
use App\Models\Currency as CurrencyModel;

class Account
{
    /**
     * @var string
     */
    public readonly string $id;
    /**
     * @var Currency|null
     */
    private null|CurrencyModel $currency = null;
    /**
     * @var array
     */
    private array $currencyToMoneyAmount = [];
    /**
     * @var array
     */
    private array $currencyToClass = [];
    /**
     * @var Customer
     */
    private CustomerModel $customer;

    /**
     * @param Customer $customer
     */
    public function __construct(CustomerModel $customer)
    {
        $this->id = uniqid();
        $this->customer = $customer;
    }

    /**
     * @param Currency $currency
     * @return void
     */
    public function addCurrency(CurrencyModel $currency): void
    {
        if (!in_array($currency->name, $this->currencyToMoneyAmount, true)) {
            $this->currencyToMoneyAmount[$currency->name] = '0';
        }
        if (!in_array($currency->name, $this->currencyToClass, true)) {
            $this->currencyToClass[$currency->name] = $currency::class;
        }
    }

    /**
     * @param Currency $currency
     * @return void
     * @throws CurrencyDoesNotExistInAccountException
     * @throws CurrencyRemoveException
     */
    public function removeCurrency(CurrencyModel $currency): void
    {
        $this->checkCurrencyExist($currency);

        if ($currency instanceof $this->currency) {
            throw new CurrencyRemoveException('Main currency cannot be removed');
        }

        $currency->setValue($this->currencyToMoneyAmount[$currency->name]);

        $toCurrency = new $this->currencyToClass[$this->currency->name]($currency);

        $this->currencyToMoneyAmount[$this->currency->name] = (string)((float)$this->currencyToMoneyAmount[$this->currency->name] + (float)$toCurrency->value);
        unset($this->currencyToMoneyAmount[$currency->name], $this->currencyToClass[$currency->name]);
    }

    /**
     * @param Currency $currency
     * @return void
     */
    public function setMainCurrency(CurrencyModel $currency): void
    {
        $this->currency = $currency;
        return;
    }

    /**
     * @return string
     */
    public function getMainCurrency(): string
    {
        return $this->currency->name;
    }

    /**
     * @return array
     */
    public function getAllCurrencies(): array
    {
        return array_keys($this->currencyToMoneyAmount);
    }

    /**
     * @param Currency $currency
     * @return void
     */
    public function deposit(CurrencyModel $currency): void
    {
        $currencyDeposit = $currency->name;
        $valueDeposit = (float)$currency->value;

        if (isset($this->currencyToMoneyAmount[$currencyDeposit])) {
            $this->currencyToMoneyAmount[$currencyDeposit] = (string)((float)$this->currencyToMoneyAmount[$currencyDeposit] + $valueDeposit);
        } else {
            $this->currencyToMoneyAmount[$currencyDeposit] = $valueDeposit;
        }
    }

    /**
     * @param Currency|null $currency
     * @return string
     * @throws BaseCurrencyNotSet
     * @throws CurrencyDoesNotExistInAccountException
     */
    public function getDeposit(null|CurrencyModel $currency = null): string
    {
        if (is_null($this->currency) && is_null($currency)) {
            throw new BaseCurrencyNotSet();
        }

        if (!is_null($currency)) {
            $this->checkCurrencyExist($currency);
            return $this->currencyToMoneyAmount[$currency->name];
        }

        return $this->getDepositDefault();
    }

    /**
     * @return string
     */
    private function getDepositDefault(): string
    {
        $currencies = $this->getAllCurrencies();
        unset($currencies[array_flip($currencies)[$this->currency->name]]);
        $amount = (float)$this->currencyToMoneyAmount[$this->currency->name];

        foreach ($currencies as $currencyItem) {
            $currencyObj = new $this->currencyToClass[$currencyItem]($this->currencyToMoneyAmount[$currencyItem]);
            $toCurrency = new $this->currencyToClass[$this->currency->name]($currencyObj);
            $amount += $toCurrency->value;
        }
        return (string)$amount;
    }

    /**
     * @param Currency $currency
     * @return Currency
     * @throws AmountWithdrawShouldNotZeroException
     * @throws CurrencyDoesNotExistInAccountException
     * @throws InsufficientFundsException
     */
    public function withdraw(CurrencyModel $currency): CurrencyModel
    {
        $this->checkCurrencyExist($currency);

        if ($currency->value === '0') {
            throw new AmountWithdrawShouldNotZeroException();
        }

        $deposit = (float)$this->currencyToMoneyAmount[$currency->name];
        $withdrawAmount = (float)$currency->value;

        if ($withdrawAmount > $deposit) {
            throw new InsufficientFundsException();
        }

        $this->currencyToMoneyAmount[$currency->name] = (string)($deposit - $withdrawAmount);
        return $currency;
    }

    /**
     * @param Currency $currency
     * @return void
     * @throws CurrencyDoesNotExistInAccountException
     */
    private function checkCurrencyExist(CurrencyModel $currency): void
    {
        if (!isset($this->currencyToMoneyAmount[$currency->name])) {
            throw new CurrencyDoesNotExistInAccountException();
        }
    }
}