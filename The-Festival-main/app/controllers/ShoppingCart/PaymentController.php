<?php
//require_once __DIR__ . '/vendor/autoload.php';
//
//
//class PaymentController
//{
//
//    private $mollie;
//
//    public function __construct()
//    {
//        $this->mollie = new \Mollie\Api\MollieApiClient();
//        $this->mollie->setApiKey('test_Ds3fz4U9vNKxzCfVvVHJT2sgW5ECD8');
//    }
//
//    public function createPayment($amount, $description, $redirectUrl, $webhookUrl)
//    {
//        $payment = $this->mollie->payments->create([
//            "amount" => [
//                "currency" => "EUR",
//                "value" => $amount
//            ],
//            "description" => $description,
//            "redirectUrl" => $redirectUrl,
//            "webhookUrl" => $webhookUrl
//        ]);
//
//        return $payment;
//    }
//    public function payNow(){
//        // Process payment form submission
//        if (isset($_POST['payNow'])) {
//            // Get payment parameters from form submission
//            $amount = $_POST["amount"];
//            $description = $_POST["description"];
//            $redirectUrl = $_POST["redirectUrl"];
//            $webhookUrl = $_POST["webhookUrl"];
//
//            // Create payment controller with test API key
//            $paymentController = new PaymentController('test_API_key');
//
//            // Create Mollie payment
//            $payment = $paymentController->createPayment($amount, $description, $redirectUrl, $webhookUrl);
//
//            // Redirect user to Mollie payment page
//            header("Location: " . $payment->getCheckoutUrl());
//            exit();
//        }
//    }
//}
//
