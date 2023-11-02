<?php
//echo //$grand_total; exit;
//$saleDet=$this->db->get_where('sale',array('order_id'=>$order_id))->result_array();
//$saleDet=$saleDet[0];
$shipping_address=$this->db->get_where('shipping_address',array('id'=>$address_unicid))->result_array();
$shipping_address=$shipping_address[0];
$productinfo = 'Shopping';
$txnid = time();
$surl = $surl;
$furl = $furl;        
$key_id = RAZOR_KEY_ID;
$currency_code = $currency_code;  
//$grand_total=1;
$total = ($grand_total* 100); 
$amount = $grand_total;
$merchant_order_id = $order_id;
//$card_holder_name = 'Pepyourcar';
$card_holder_name = $customer_name;
//$email = $email;
//$phone = $phone;
//$name = 'Pepyourcar';
$name = $customer_name;
$return_url = $return_url;
$home_top_logo = $this->db->get_where('ui_settings',array('type' => 'home_top_logo'))->row()->value;
$image=base_url() . 'uploads/logo_image/logo_'.$home_top_logo.'.png';
?>

 <form name="razorpay-form" id="razorpay-form" action="<?php echo $return_url; ?>" method="POST">
  <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" />
  <input type="hidden" name="merchant_order_id" id="merchant_order_id" value="<?php echo $merchant_order_id; ?>"/>
  <input type="hidden" name="merchant_trans_id" id="merchant_trans_id" value="<?php echo $txnid; ?>"/>
  <input type="hidden" name="merchant_product_info_id" id="merchant_product_info_id" value="<?php echo $productinfo; ?>"/>
  <input type="hidden" name="merchant_surl_id" id="merchant_surl_id" value="<?php echo $surl; ?>"/>
  <input type="hidden" name="merchant_furl_id" id="merchant_furl_id" value="<?php echo $furl; ?>"/>
  <input type="hidden" name="card_holder_name_id" id="card_holder_name_id" value="<?php echo $card_holder_name; ?>"/>
  <input type="hidden" name="merchant_total" id="merchant_total" value="<?php echo $total; ?>"/>
  <input type="hidden" name="merchant_amount" id="merchant_amount" value="<?php echo $amount; ?>"/>
</form>
    

    <?php /*?><div class="row">
        <div class="col-lg-12 text-right">
            <a href="<?php print site_url();?>" name="reset_add_emp" id="re-submit-emp" class="btn btn-warning"><i class="fa fa-mail-reply"></i> Back</a>
            <input id="submit-pay" type="submit"  value="Pay Now" class="btn btn-primary" />
        </div>
    </div><?php */?>
 


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  var razorpay_options = {
    key: "<?php echo $key_id; ?>",
    amount: "<?php echo $total; ?>",
    name: "<?php echo $name; ?>",
    description: "Order # <?php echo $merchant_order_id; ?>",
    netbanking: true,
    currency: "<?php echo $currency_code; ?>",
    image: "<?php echo $image; ?>",
    prefill: {
      name:"<?php echo $card_holder_name; ?>",
      email: "<?php echo $email; ?>",
      contact: "<?php echo $phone; ?>"
    },
    notes: {
      soolegal_order_id: "<?php echo $merchant_order_id; ?>",
    },
    handler: function (transaction) {
        document.getElementById('razorpay_payment_id').value = transaction.razorpay_payment_id;
        document.getElementById('razorpay-form').submit();
    },
    "modal": {
        "ondismiss": function(){
           // location.reload()
           window.location.replace("<?php echo $furl; ?>");
        }
    }
  };
  
 var razorpay_submit_btn, razorpay_instance;


    if(typeof Razorpay == 'undefined'){
      setTimeout(razorpaySubmit, 200);
      if(!razorpay_submit_btn && el){
        razorpay_submit_btn = el;
        el.disabled = true;
        el.value = 'Please wait...';  
      }
    } else {
      if(!razorpay_instance){
        razorpay_instance = new Razorpay(razorpay_options);
        if(razorpay_submit_btn){
          razorpay_submit_btn.disabled = false;
          razorpay_submit_btn.value = "Pay Now";
        }
      }
      razorpay_instance.open();
    }
 

</script>
