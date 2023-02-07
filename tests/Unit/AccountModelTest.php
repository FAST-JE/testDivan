<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\EUR as EURModel;
use App\Models\RUB as RUBModel;
use App\Models\USD as USDModel;
use App\Models\Customer as CustomerModel;
use App\Models\Account as AccountModel;
use App\Models\Bank as BankModel;

class AccountModelTest extends TestCase
{
    private bankModel $bank;
    private CustomerModel $customer;

    private AccountModel $account;
    protected function setUp(): void {
        $this->bank = new BankModel();
        $this->customer = new CustomerModel('Dmitry', 'Tarasov');
        $this->account = $this->bank->createAccount($this->customer);
        $this->account->addCurrency(new RUBModel());
        $this->account->addCurrency(new EURModel());
        $this->account->addCurrency(new USDModel());
        $this->account->setMainCurrency(new RUBModel());
        $this->account->deposit(new RUBModel(1000));
        $this->account->deposit(new EURModel(50));
        $this->account->deposit(new USDModel(50));
        parent::setUp();
    }

    public function testCheckCurrencies(): void
    {
        $expected = [
            'RUB',
            'EUR',
            'USD'
        ];

        $this->assertEquals($expected, $this->account->getAllCurrencies());
    }

    public function testCheckDeposit(): void
    {
        $this->assertEquals('11500', $this->account->getDeposit());
        $this->assertEquals('1000', $this->account->getDeposit(new RUBModel));
        $this->assertEquals('50', $this->account->getDeposit(new USDModel));
        $this->assertEquals('50', $this->account->getDeposit(new EURModel));
    }

    public function testCheckWithdraw(): void
    {
        $this->account->withdraw(new USDModel(10));

        $this->assertEquals('40', $this->account->getDeposit(new USDModel));
    }

    public function testChangeMainCurrency(): void
    {
        $this->account->setMainCurrency(new EURModel);

        $this->assertEquals('115', $this->account->getDeposit());
    }

    public function testWithdrawAndConvert(): void
    {
        $this->account->setMainCurrency(new EURModel);
        $amountWithdraw = $this->account->withdraw(new RUBModel(1000));
        $this->account->deposit(new EURModel($amountWithdraw));

        $this->assertEquals('115', $this->account->getDeposit());
    }

    public function testRemoveCurrencies(): void
    {
        $this->account->setMainCurrency(new RUBModel());
        $this->account->removeCurrency(new EURModel());
        $this->account->removeCurrency(new USDModel());

        $this->assertEquals('11500', $this->account->getDeposit());
    }
}