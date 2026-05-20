<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/Iyzipay/IyzipayBootstrap.php'; // Ensure SDK is included

class Iyzico {
    private $apiKey;
    private $secretKey;
    private $baseUrl;

    public function __construct() {
        $CI =& get_instance();
    }

    public function createPayment($paymentData) {
        $options = new \Iyzipay\Options();
        $options->setApiKey($paymentData['apiKey']);
        $options->setSecretKey($paymentData['secretKey']);
        $options->setBaseUrl($paymentData['baseUrl']);

        $request = new \Iyzipay\Request\CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($paymentData['conversationId']);
        $request->setPrice($paymentData['price']);
        $request->setPaidPrice($paymentData['paidPrice']);
        //$request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setCurrency(constant("\Iyzipay\Model\Currency::".$paymentData['currency']));
        $request->setInstallment(1);
        $request->setBasketId($paymentData['basketId']);
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        
        // Card information
        $paymentCard = new \Iyzipay\Model\PaymentCard();
        $paymentCard->setCardHolderName($paymentData['cardHolderName']);
        $paymentCard->setCardNumber($paymentData['cardNumber']);
        $paymentCard->setExpireMonth($paymentData['expireMonth']);
        $paymentCard->setExpireYear($paymentData['expireYear']);
        $paymentCard->setCvc($paymentData['cvc']);
        $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

        // Buyer info
        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($paymentData['buyerId']);
        $buyer->setName($paymentData['buyerName']);
        $buyer->setSurname($paymentData['buyerSurname']);
        $buyer->setGsmNumber($paymentData['buyerPhone']);
        $buyer->setEmail($paymentData['buyerEmail']);
        $buyer->setIdentityNumber($paymentData['buyerIdentityNumber']);
        $buyer->setRegistrationAddress($paymentData['buyerAddress']);
        $buyer->setIp($paymentData['buyerIp']);
        $buyer->setCity($paymentData['buyerCity']);
        $buyer->setCountry($paymentData['buyerCountry']);
        $request->setBuyer($buyer);

        // Address Information
        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName("Jane Doe");
        $shippingAddress->setCity("Istanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("Istanbul, Turkey");
        $shippingAddress->setZipCode("34000");
        $request->setShippingAddress($shippingAddress);

        // Billing Address (Explicitly added)
        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName("John Doe");
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("Istanbul, Turkey");
        $billingAddress->setZipCode("34000");
        $request->setBillingAddress($billingAddress);

        // Basket Item for Single Plan/Booking
        $basketItems = [];
        $basketItem = new \Iyzipay\Model\BasketItem();
        $basketItem->setId("BI101");
        $basketItem->setName("Booking Plan");  // Plan or booking name
        $basketItem->setCategory1("Service");
        $basketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);  // Virtual since it's not a physical item
        $basketItem->setPrice($paymentData['price']); // Single item price
        $basketItems[] = $basketItem;

        // Set Basket Items and Prices
        $request->setBasketItems($basketItems);
        $request->setPrice($paymentData['price']);
        $request->setPaidPrice($paymentData['paidPrice']);

        $payment = \Iyzipay\Model\Payment::create($request, $options);
        return $payment;
    }
}