<?php

require_once('config.php');

# create request class
$request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
$request->setLocale(\Iyzipay\Model\Locale::EN);
$request->setConversationId($transaction_id);
$request->setPrice($_SESSION['p']['samount']);
$request->setPaidPrice($_SESSION['p']['samount']);
$request->setCurrency(\Iyzipay\Model\Currency::USD);
$request->setBasketId($transaction_id);
$request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
$request->setCallbackUrl("https://hakan.myrunciit.my/index.php/home/iyzipay_success");
$request->setEnabledInstallments(array(2, 3, 6, 9));


$buyer = new \Iyzipay\Model\Buyer();
$buyer->setId($_SESSION['p']['client_id']);
$buyer->setName($sh['firstname']);
$buyer->setSurname("imhap");
$buyer->setGsmNumber($sh['phone']);
$buyer->setEmail($sh['email']);
$buyer->setIdentityNumber($sh['idcard']);
$buyer->setLastLoginDate("2021-02-05 12:43:35");
$buyer->setRegistrationDate("2021-01-21 15:12:09");
$buyer->setRegistrationAddress($sh['address1'].','.$sh['address2'] );
$buyer->setIp("85.34.78.112");
$buyer->setCity($sh['cities']);
$buyer->setCountry($sh['country']);
$buyer->setZipCode($sh['zip']);
$request->setBuyer($buyer);
//print_r($request);
//echo"<br/>";
//print_r($buyer); exit;

$shippingAddress = new \Iyzipay\Model\Address();
$shippingAddress->setContactName("Jane Doe");
$shippingAddress->setCity("Istanbul");
$shippingAddress->setCountry("Turkey");
$shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
$shippingAddress->setZipCode("34742");
$request->setShippingAddress($shippingAddress);

$billingAddress = new \Iyzipay\Model\Address();
$billingAddress->setContactName("Jane Doe");
$billingAddress->setCity("Istanbul");
$billingAddress->setCountry("Turkey");
$billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
$billingAddress->setZipCode("34742");
$request->setBillingAddress($billingAddress);

$basketItems = array();
$firstBasketItem = new \Iyzipay\Model\BasketItem();
$firstBasketItem->setId("BI101");
$firstBasketItem->setName("Binocular");
$firstBasketItem->setCategory1("Collectibles");
$firstBasketItem->setCategory2("Accessories");
$firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
$firstBasketItem->setPrice($_SESSION['p']['samount']);
$basketItems[0] = $firstBasketItem;


$request->setBasketItems($basketItems);

# make request
$checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, Config::options());

# print result
print_r($checkoutFormInitialize);
echo $checkoutFormInitialize['rawResult:Iyzipay\ApiResource:private'];
$var=json_decode($checkoutFormInitialize['[rawResult:Iyzipay\ApiResource:private]'],1);
print_r($var);