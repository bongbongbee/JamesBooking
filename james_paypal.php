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

$jamesBaseUrl = site_url();
//thestudyarea@gmail.com
//201523756w
$jamesSuccessUrl = "$jamesBaseUrl/booking-success";
$jamesFailUrl    = "$jamesBaseUrl/booking-failure";
//James
// $jamesClientId     = 'AU26pQeqXHj1_1FlUTSlSTLu1LKPtax9O7E1zG898sl72nGmwtFBNmkQTgOtPr_dfOknK8aqiCvMh5Sy';
// $jamesClientSecret = 'EJ8eQfryyRM4_qE3w9VX32KLgD18vXzv9BFxj2OcEccenXqmFaGw05BD3A0cthw3skN0r41UgzwPzTam';
$jamesClientId     = 'ATAo58tHcl99h2jDmTCzqfLyq4Tzzo_UczICiHydpxwkuwrsYnSHT-pj7ej3mgJZ8ubRYUlglJuwO2Ac';
$jamesClientSecret = 'EFPjxAprHXSoWay-V0xXpX-sozMCAPQE1wdle7HjLfj9rdsQ6gX-ICydRU-I5_Do4cbjH0_YbmuQzVl0';

$paypalMode = 'sandbox';

function addAndRedirectPayment($one_table_cost, $num_of_tables, $item_name, $ref_no)
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
    $item1->setName($item_name)
        ->setCurrency('SGD')
        ->setQuantity($num_of_tables)
        ->setSku($ref_no) // Similar to `item_number` in Classic API
        ->setPrice($one_table_cost);

    $itemList = new ItemList();
    $itemList->setItems(array($item1));

    // ### Amount
    // Lets you specify a payment amount.
    // You can also specify additional details
    // such as shipping, tax.
    $amount = new Amount();
    $amount->setCurrency("SGD")
        ->setTotal($one_table_cost * intval($num_of_tables))
        ->setDetails($details);

// ### Transaction
    // A transaction defines the contract of a
    // payment - what is the payment for and who
    // is fulfilling it.
    $transaction = new Transaction();
    $transaction
        ->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Payment description")
        ->setInvoiceNumber(uniqid());

// ### Redirect urls
    // Set the urls that the buyer must be redirected to after
    // payment approval/ cancellation.

    $redirectUrls = new RedirectUrls();
    global $jamesSuccessUrl, $jamesFailUrl, $jamesBaseUrl;
    $redirectUrls->setReturnUrl($jamesSuccessUrl . "?slotId=$ref_no")
        ->setCancelUrl($jamesFailUrl
        );

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
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
        echo $ex->getCode(); // Prints the Error Code
        echo $ex->getData(); // Prints the detailed error message
        die($ex);
    } catch (Exception $ex) {
        echo $ex;

        exit(1);
    }

// ### Get redirect url
    // The API response provides the url that you must redirect
    // the buyer to. Retrieve the url from the $payment->getApprovalLink()
    // method
    $approvalUrl = $payment->getApprovalLink();

    wp_redirect($approvalUrl);
    exit;

}

function get_api_context()
{
    global $jamesClientId, $jamesClientSecret, $paypalMode;

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            $jamesClientId, // ClientID
            $jamesClientSecret // ClientSecret
        )
    );
//test james

    $apiContext->setConfig(
        array(
            'mode'           => $paypalMode,
            'log.LogEnabled' => true,
            'log.FileName'   => 'PayPal.log',
            'log.LogLevel'   => 'FINE',
        )
    );
    return $apiContext;
}

function receive_paypal_payment($slotId)
{
    $paypalPaid = get_post_meta($slotId, 'paypalPaid', true);
    if ($paypalPaid != "true") {
        $apiContext = get_api_context();
        $paymentId  = $_GET['paymentId'];
        $payment    = Payment::get($paymentId, $apiContext);
        $execution  = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);

        $transaction = new Transaction();
        $amount      = new Amount();
        $details     = new Details();

        $amount->setCurrency('SGD');
        $amount->setTotal($payment->getTransactions()[0]->getAmount()->getTotal());
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        try {
            $result = $payment->execute($execution, $apiContext);
            add_post_meta($slotId, 'paypalPaid', 'true');
            add_post_meta($slotId, 'paypalPayerID', $_GET['PayerID']);
            add_post_meta($slotId, 'paypalPaymentID', $_GET['paymentId']);
            try {
                $payment = Payment::get($paymentId, $apiContext);
                return true;
            } catch (Exception $ex) {
                echo $ex;
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode(); // Prints the Error Code
            echo $ex->getData(); // Prints the detailed error message
            die($ex);
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    else
        return false;
}
