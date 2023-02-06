<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Models\EUR as ModelEUR;
use App\Models\RUB as ModelRUB;
use App\Models\USD as ModelUSD;

$bankModel = new App\Models\Bank();
$customerModel = new App\Models\Customer('dima', 'tarasov');

$account = $bankModel->createAccount($customerModel);
$account->addCurrency(new ModelEUR);
$account->addCurrency(new ModelRUB);
$account->setMainCurrency(new ModelRUB);
$account->deposit(new ModelRUB(100));
var_dump($account->getDeposit());
$account->withdraw(new ModelRUB(10));
var_dump($account->getDeposit());
var_dump(new ModelRUB());
//var_dump($account);