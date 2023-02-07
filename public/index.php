<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Models\EUR as EURModel;
use App\Models\RUB as RUBModel;
use App\Models\USD as USDModel;

$bankModel = new App\Models\Bank();
$customerModel = new App\Models\Customer('dima', 'tarasov');

$account = $bankModel->createAccount($customerModel);
$account->addCurrency(new RUBModel());
$account->addCurrency(new EURModel());
$account->addCurrency(new USDModel());
$account->setMainCurrency(new RUBModel());
$account->deposit(new RUBModel(1000));
$account->deposit(new EURModel(50));
$account->deposit(new USDModel(50));
$account->withdraw(new USDModel(10));
$amountWithdraw = $account->withdraw(new RUBModel(1000));
$account->deposit(new EURModel($amountWithdraw));
var_dump($account);
//var_dump($account->getDeposit());
//$account->setMainCurrency(new USDModel());
//var_dump($account->getDeposit());