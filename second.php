<?php
// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
// Used for composer based installation
require __DIR__ . '/paypal_sdk/autoload.php';

// After Step 1
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AU26pQeqXHj1_1FlUTSlSTLu1LKPtax9O7E1zG898sl72nGmwtFBNmkQTgOtPr_dfOknK8aqiCvMh5Sy', // ClientID
        'EJ8eQfryyRM4_qE3w9VX32KLgD18vXzv9BFxj2OcEccenXqmFaGw05BD3A0cthw3skN0r41UgzwPzTam' // ClientSecret
    )
);

// After Step 2
$creditCard = new \PayPal\Api\CreditCard();
$creditCard->setType("visa")
    ->setNumber("4417119669820331")
    ->setExpireMonth("11")
    ->setExpireYear("2019")
    ->setCvv2("012")
    ->setFirstName("Joe")
    ->setLastName("Shopper");

// Step 2.1 : Between Step 2 and Step 3
$apiContext->setConfig(
  array(
    'log.LogEnabled' => true,
    'log.FileName' => 'PayPal.log',
    'log.LogLevel' => 'FINE'
  )
);

// After Step 3
try {
    $creditCard->create($apiContext);
    echo $creditCard;
} catch (\PayPal\Exception\PayPalConnectionException $ex) {
    // This will print the detailed information on the exception.
    //REALLY HELPFUL FOR DEBUGGING
    echo $ex->getData();
}
