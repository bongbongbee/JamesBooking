<?php
// 1. Autoload the SDK Package. This will include all the files and classes to your autoloader
// Used for composer based installation
require __DIR__ . '/paypal_sdk/autoload.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

function addCreditCard()
{
    // After Step 1
    $apiContext = get_api_context();

// ### Payer
    // A resource representing a Payer that funds a payment
    // For paypal account payments, set payment method
    // to 'paypal'.
    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

// ### Itemized information
    // (Optional) Lets you specify item wise
    // information
    $item1 = new Item();
    $item1->setName('Ground Coffee 40 oz')
        ->setCurrency('USD')
        ->setQuantity(1)
        ->setSku("123123") // Similar to `item_number` in Classic API
        ->setPrice(7.5);
    $item2 = new Item();
    $item2->setName('Granola bars')
        ->setCurrency('USD')
        ->setQuantity(5)
        ->setSku("321321") // Similar to `item_number` in Classic API
        ->setPrice(2);

    $itemList = new ItemList();
    $itemList->setItems(array($item1, $item2));

// ### Additional payment details
    // Use this optional field to set additional
    // payment information such as tax, shipping
    // charges etc.
    $details = new Details();
    $details->setShipping(1.2)
        ->setTax(1.3)
        ->setSubtotal(17.50);

// ### Amount
    // Lets you specify a payment amount.
    // You can also specify additional details
    // such as shipping, tax.
    $amount = new Amount();
    $amount->setCurrency("USD")
        ->setTotal(20)
        ->setDetails($details);

// ### Transaction
    // A transaction defines the contract of a
    // payment - what is the payment for and who
    // is fulfilling it.
    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Payment description")
        ->setInvoiceNumber(uniqid());

// ### Redirect urls
    // Set the urls that the buyer must be redirected to after
    // payment approval/ cancellation.
    $baseUrl      = "http://localhost/wordpress/booking";
    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl("$baseUrl?success=true")
        ->setCancelUrl("$baseUrl?success=false");

// ### Payment
    // A Payment Resource; create one using
    // the above types and intent set to 'sale'
    $payment = new Payment();
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrls)
        ->setTransactions(array($transaction));

// ### Create Payment
    // Create a payment by calling the 'create' method
    // passing it a valid apiContext.
    // (See bootstrap.php for more on `ApiContext`)
    // The return object contains the state and the
    // url to which the buyer must be redirected to
    // for payment approval
    try {
        $payment->create($apiContext);
    } catch (Exception $ex) {
        echo $ex;

        exit(1);
    }

// ### Get redirect url
    // The API response provides the url that you must redirect
    // the buyer to. Retrieve the url from the $payment->getApprovalLink()
    // method
    $approvalUrl = $payment->getApprovalLink();

    echo "Approval URL : $approvalUrl";

}

function get_api_context()
{
    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'AU26pQeqXHj1_1FlUTSlSTLu1LKPtax9O7E1zG898sl72nGmwtFBNmkQTgOtPr_dfOknK8aqiCvMh5Sy', // ClientID
            'EJ8eQfryyRM4_qE3w9VX32KLgD18vXzv9BFxj2OcEccenXqmFaGw05BD3A0cthw3skN0r41UgzwPzTam' // ClientSecret
        )
    );

    $apiContext->setConfig(
        array(
            'log.LogEnabled' => true,
            'log.FileName'   => 'PayPal.log',
            'log.LogLevel'   => 'FINE',
        )
    );
    return $apiContext;
}

function receive_paypal_payment()
{
    $apiContext = get_api_context();
    $paymentId = $_GET['paymentId'];
    $payment   = Payment::get($paymentId, $apiContext);
    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    $transaction = new Transaction();
    $amount      = new Amount();
    $details     = new Details();

    $details->setShipping(2.2)
        ->setTax(1.3)
        ->setSubtotal(17.50);

    $amount->setCurrency('USD');
    $amount->setTotal(21);
    $amount->setDetails($details);
    $transaction->setAmount($amount);

    // Add the above transaction object inside our Execution object.
    $execution->addTransaction($transaction);

    try {
        // Execute the payment
        // (See bootstrap.php for more on `ApiContext`)
        $result = $payment->execute($execution, $apiContext);
        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (Exception $ex) {
            echo $ex;
        }
    } catch (Exception $ex) {
        echo $ex;
    }
}
