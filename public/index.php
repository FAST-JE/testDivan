<?php

require_once __DIR__.'/../vendor/autoload.php';
//require_once __DIR__.'/../src/Enums/Enums.php';
//use App\Enums\Currency;


$bankModel = new App\Models\Bank();
$customerModel = new App\Models\Customer('dima', 'tarasov');

//var_dump($customerModel);

$bankService = new App\Services\Bank($bankModel);
$account = $bankService->createAccount($customerModel);
$account->addCurrency(new \App\Models\RUB);
$account->addCurrency(new \App\Models\EUR);
//$account->setMainCurrency(new \App\Models\RUB);
$account->deposit(new \App\Models\RUB(100));
var_dump($account->getDeposit());













//$account2 = $bankService->createAccount($customerModel);
//var_dump($bankService->getAllAccountUser($customerModel));

//var_dump($bankModel);
//$bankModel->setExchange(App\Enums\Currency::RUB, App\Enums\Currency::USD, 150);
//var_dump($bankModel);