	<?php
	//live
	//$merchant_key='oCVSOT0H6T';
	//$merchant_code='M37540';
	
	//demo
	//$merchant_key='SYT3n1nSjc';
	//$merchant_code='M17669';
	$merchant_key='zNl8ySkJmk';
	$merchant_code='M35362';
	/*$merchant_key='oUTdzQOHqK';
	$merchant_code='M27504';*/
	$currency="MYR";
	$amount="1";
	$source=$merchant_key.$merchant_code.$aut_id.$amount.$currency;
	$signature=hash('sha256',$source); 
	?>
	<html>
<head>
<title> Checkout</title>
</head>
<body>
    	

<form method="post" name="ePayment" id="ePayment" action="https://payment.ipay88.com.my/ePayment/entry.asp">
<input type="hidden" name="MerchantCode" value="<?php echo $merchant_code; ?>">
<input type="hidden" name="PaymentId" value="">
<input type="hidden" name="Plan" value="">
<input type="hidden" name="RefNo" value="<?php echo $aut_id; ?>">
<input type="hidden" name="Amount" value="<?php echo $amount; ?>">
<input type="hidden" name="Currency" value="<?php echo $currency; ?>">
<input type="hidden" name="ProdDesc" value="shopping">
<input type="hidden" name="UserName" value="<?php echo $client_name; ?>">
<input type="hidden" name="UserEmail" value="<?php echo $client_email; ?>">
<input type="hidden" name="UserContact" value="<?php echo $client_phone; ?>">
<input type="hidden" name="Remark" value="">
<input type="hidden" name="Lang" value="UTF-8">
<input type="hidden" name="SignatureType" value="SHA256">
<input type="hidden" name="Signature" value="<?php echo $signature; ?>">
<input type="hidden" name="ResponseURL" value="<?php echo $return_page; ?>">
<input type="hidden" name="BackendURL" value="<?php echo $backend_page; ?>">
<!--<input type="text" name="ActionType" value="">
<input type="text" name="TokenId" value="">-->
<input type="submit" value="Proceed with Payment" name="Submit"  style="visibility:hidden;"> </form>

<script type="text/javascript">
document.getElementById("ePayment").submit();
</script>
</body>
</html>



    
     
