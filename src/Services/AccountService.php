<?php

namespace App\Services;

use App\Exceptions\AmountWithdrawShouldNotZeroException;
use App\Exceptions\CurrencyDoesNotExistInAccountException;
use App\Exceptions\CurrencyRemoveException;
use App\Exceptions\InsufficientFundsException;
use App\Models\Bank as BankModel;
use App\Models\Currency as CurrencyModel;
use App\Models\Customer as CustomerModel;
use App\Models\Account as AccountModel;

class AccountService
{
    private AccountModel $account;
    public function __construct(AccountModel $account)
    {
        $this->account = $account;
    }

    public function addCurrency(CurrencyModel $currency): void
    {
        if (gettype($this->account->currency) === 'NULL')
            $this->account->currency = $currency;

        if (!in_array($currency, $this->account->currencyArr)) {
            $this->account->currencyArr[] = $currency;
        }
    }

    public function removeCurrency(CurrencyModel $currency): void
    {
        $this->checkCurrencyExist($currency);

        if ($currency instanceof $this->account->currency)
            throw new CurrencyRemoveException('Main currency cannot be removed');

        $this->account->currencyToMoneyAmount[$this->account->currency->name] = $this->currencyConvert($currency, $this->account->currency);

        return;
    }

    public function setMainCurrency(CurrencyModel $currency): void
    {
        $this->account->currency = $currency;
        return;
    }

    public function getMainCurrency(): string
    {
        return $this->account->currency->name;
    }

    public function getAllCurrencies(): array
    {
        return array_map(fn($value): string => $value->name, $this->account->currencyArr);
    }

    public function deposit(CurrencyModel $currency, null|CurrencyModel $toCurrency = null): void
    {
        $currencyDeposit = $currency->name;
        $valueDeposit = $currency->value;

        if (gettype($currency) !== 'NULL') {
            $currencyDeposit = $toCurrency->name;
            $valueDeposit = $this->currencyConvert($currency, $toCurrency);
        }

        if (isset($this->account->currencyToMoneyAmount[$currencyDeposit])) {
            $this->account->currencyToMoneyAmount[$currencyDeposit] = strval(floatval($this->account->currencyToMoneyAmount[$currencyDeposit]) + floatval($valueDeposit));
        } else {
            $this->account->currencyToMoneyAmount[$currencyDeposit] = $valueDeposit;
        }

        return;
    }

    public function getDeposit(null|CurrencyModel $currency = null): string
    {
        $getCurrency = $this->account->currency->name;
        if (gettype($currency) !== 'NULL') {
            $getCurrency = $currency->name;
        }
        $this->checkCurrencyExist($currency);

        return $this->account->currencyToMoneyAmount[$getCurrency];
    }

    public function withdraw(CurrencyModel $currency): void
    {
        $this->checkCurrencyExist($currency);

        if ($currency->value === '0')
            throw new AmountWithdrawShouldNotZeroException();

        $deposit = floatval($this->account->currencyToMoneyAmount[$currency->name]);
        $withdrawAmount = floatval($currency->value);

        if ($withdrawAmount > $deposit)
            throw new InsufficientFundsException();

        $this->account->currencyToMoneyAmount[$currency->name] = strval($deposit - $withdrawAmount);
    }

    private function checkCurrencyExist(CurrencyModel $currency): void
    {
        if (!isset($this->currencyToMoneyAmount[$currency->name]))
            throw new CurrencyDoesNotExistInAccountException();

        return;
    }

    private function currencyConvert(CurrencyModel $currency, CurrencyModel $toCurrency): string
    {
        $exchangeRate = $this->account->boundBank->exchangeRates[$currency->name . '/' . $toCurrency->name];
        return strval($currency->value * $exchangeRate);
    }
}