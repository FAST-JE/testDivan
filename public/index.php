<?php

require_once __DIR__.'/../vendor/autoload.php';
//require_once __DIR__.'/../src/Enums/Enums.php';
//use App\Enums\Currency;


$bankModel = new App\Models\Bank();
$customerModel = new App\Models\Customer('dima', 'tarasov');

//var_dump($customerModel);

$bankService = new App\Services\BankService($bankModel);
$account = $bankService->createAccount($customerModel);
$bankService->accountService->addCurrency(new \App\Models\RUB);
var_dump($bankService->);

//try {
//    $account->getDeposit(new \App\Models\EUR);
//} catch (App\Exceptions\CurrencyDoesNotExistInAccountException $e) {
//    var_dump($e->getMessage());
//}
var_dump($account->removeCurrency(new \App\Models\USD));













//$account2 = $bankService->createAccount($customerModel);
//var_dump($bankService->getAllAccountUser($customerModel));

//var_dump($bankModel);
//$bankModel->setExchange(App\Enums\Currency::RUB, App\Enums\Currency::USD, 150);
//var_dump($bankModel);