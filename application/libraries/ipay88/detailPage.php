<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    include APPPATH . 'views/front/includes/top/index.php';
    ?>
</head>

<body>
    <?php
    $onlineBankingPaymentMethods = [
        'Maybank2U' => 6,
        'Alliance Online (Personal)' => 8,
        'AmBank' => 10,
        'RHB Bank' => 14,
        'Hong Leong Bank' => 15,
        'CIMB Clicks' => 20,
        'Public Bank' => 31,
        'Bank Rakyat' => 102,
        'Affin Bank' => 103,
        'Pay4Me (Delay payment)' => 122,
        'BSN' => 124,
        'Bank Islam' => 134,
        'UOB Bank' => 152,
        'Bank Muamalat' => 166,
        'OCBC Bank' => 167,
        'Standard Chartered Bank' => 168,
        'Maybank2E' => 178,
        'HSBC Bank' => 198,
        'Kuwait Finance House' => 199,
        'Agro Bank' => 405,
        'China UnionPay Online Banking (MYR)' => 18
    ];

    $creditCardPaymentMethods = [
        'Credit Card (MYR)' => 2,
        'Credit Card (MYR) Pre-Auth' => 55,
        'Public Bank EPP (Instalment Payment)' => 111,
        'Maybank EzyPay (Visa/Mastercard Instalment Payment)' => 112,
        'Maybank EzyPay (AMEX Instalment Payment)' => 115,
        'HSBC (Instalment Payment)' => 157,
        'CIMB Easy Pay (Instalment Payment)' => 174,
        'Hong Leong Bank EPP-MIGS (Instalment Payment)' => 179,
        'Hong Leong Bank EPP-MPGS (Instalment Payment)' => 433,
        'RHB (Instalment Payment)' => 534,
        'Ambank EPP' => 606
    ];

    $walletPaymentMethods = [
        'Kiple Online' => 22,
        'PayPal (MYR)' => 48,
        'Boost Wallet Online' => 210,
        'MCash' => 244,
        'NETS QR Online' => 382,
        'GrabPay Online' => 523,
        "Touch 'n Go eWallet" => 538,
        'Maybank PayQR Online' => 542,
        'ShopeePay Online' => 801,
    ];

    function iPay88_signature($source)
    {
        return hash('sha256', $source);
    }
    $allData = json_decode($this->session->userdata('allData'));
    $shipping_address = json_decode($allData->shipping_address);
    $this->session->set_userdata('MerchantCode', 'M37540');
    $this->session->set_userdata('MerchantKey', 'oCVSOT0H6T');
    $refNumber = 'RF' . substr(time(), 4) . rand(1, 10) . rand(1, 99);
    $secretString = $this->session->userdata('MerchantKey') . $this->session->userdata('MerchantCode'). $refNumber  . $allData->grand_total. 'MYR'.'';
    print_r($secretString);
    // exit;
    $secretHas = iPay88_signature($secretString);
    // echo '<pre>';
    // print_r($allData);
    // exit;
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">

                <form action="https://payment.ipay88.com.my/epayment/entry.asp" method="post" name="ePayment">
                    <input type="hidden" name="MerchantCode" value="M37540">
                    <input type="hidden" name="PaymentId" id="PaymentId" value="">
                    <input type="hidden" name="appdeeplink"  value="">
                    <input type="hidden" name="Xfield1"  value="">
                    <input type="hidden" name="RefNo" value="<?= $refNumber?>">
                    <input type="hidden" name="Amount" value="<?= $allData->grand_total?>">
                    <input type="hidden" name="Currency" value="MYR">
                    <input type="hidden" name="ProdDesc" value="Product description">
                    <input type="hidden" name="UserName" value="<?= $shipping_address->firstname?>">
                    <input type="hidden" name="UserEmail" value="<?= $shipping_address->email?>">
                    <input type="hidden" name="UserContact" value="<?= $shipping_address->address1. ' '?> <?= $shipping_address->address2?>">
                    <input type="hidden" name="Remark" value="Merchant remarks">
                    <input type="hidden" name="Lang" value="UTF-8">
                    <input type="hidden" name="SignatureType" value="SHA256">
                    <input type="hidden" name="Signature" value="<?= $secretHas ?>">
                    <input type="hidden" name="ResponseURL" value="<?php echo base_url('home/response') ?>">
                    <input type="hidden" name="BackendURL" value="<?php echo base_url('home/response') ?>">
                    <input type="hidden" name="BackendURL" value="<?php echo base_url('home/response') ?>">
                    <div class="form-group">
                        <label for="paymentType">Select Payment Type</label>
                        <select name="paymentType" id="paymentType" class="form-control">
                            <option value="">--Select Payment Type--</option>
                            <option value="onlinePayment">Online Banking Payment Method</option>
                            <option value="creditCardPaymentMethods">Credit Card Payment Method</option>
                            <option value="walletPaymentMethods">Wallet Payment Method</option>
                        </select>
                    </div>

                    <!-- Online Banking Payment Method -->
                    <div class="form-group">
                    <label for="onlineBankingPaymentMethods">Select Online Banking Type</label>
                        <select class="form-control" id="onlineBankingPaymentMethods">
                            <option value="">--Online Banking Type--</option>
                            <?php foreach ($onlineBankingPaymentMethods as $key => $value) : ?>
                                <option value="<?= $value ?>"><?= $key ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <!-- creditCardPaymentMethods -->
                    <div class="form-group">
                    <label for="creditCardPaymentMethods">Select Credit Card Type</label>
                        <select class="form-control" id="creditCardPaymentMethods">
                            <option value="">--Credit Card Type--</option>
                            <?php foreach ($creditCardPaymentMethods as $key => $value) : ?>
                                <option value="<?= $value ?>"><?= $key ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <!-- walletPaymentMethods -->
                    <div class="form-group">
                    <label for="walletPaymentMethods">Select Wallet Payment Type</label>
                        <select class="form-control" id="walletPaymentMethods">
                            <option value="">--Wallet Payment Type--</option>
                            <?php foreach ($walletPaymentMethods as $key => $value) : ?>
                                <option value="<?= $value ?>"><?= $key ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style type="text/css">
        @media print {
            .top-bar {
                display: none !important;
            }

            header {
                display: none !important;
            }

            footer {
                display: none !important;
            }

            .to-top {
                display: none !important;
            }

            .btn_print {
                display: none !important;
            }

            .invoice {
                padding: 0px;
            }

            .table {
                margin: 0px;
            }

            address {
                margin-bottom: 0px;
                border: 1px solid #fff !important;
            }
        }
    </style>
    <script>
        $(document).ready(function(){
            $('#onlineBankingPaymentMethods').parent('.form-group').hide();
            $('#creditCardPaymentMethods').parent('.form-group').hide();
            $('#walletPaymentMethods').parent('.form-group').hide();
            // $('#paymentType').change(function(e){
            //     e.preventDefault();
            //     var selectedItem = $(this).find(':selected').val();
            //     if (selectedItem == 'onlinePayment') {
            //         $('#onlineBankingPaymentMethods').parent('.form-group').show();
            //     }
            //     if (selectedItem == 'creditCardPaymentMethods') {
            //         $('#creditCardPaymentMethods').parent('.form-group').show();
            //     }
            //     if (selectedItem == 'walletPaymentMethods') {
            //         $('#walletPaymentMethods').parent('.form-group').show();
            //     }
            //     $(this).parent('.form-group').hide();
            // });
            // $('#onlineBankingPaymentMethods').change(function(e){
            //     e.preventDefault();
            //     $('#PaymentId').val($(this).val());
            // });
            // $('#creditCardPaymentMethods').change(function(e){
            //     e.preventDefault();
            //     $('#PaymentId').val($(this).val());
            // });
            // $('#walletPaymentMethods').change(function(e){
            //     e.preventDefault();
            //     $('#PaymentId').val($(this).val());
            // });
        })
    </script>
    <?php
    include APPPATH . 'views/front/script_texts.php';
    ?>
    <?php
    include 'views/front/includes/bottom/index.php';
    ?>
</body>

</html>