<?php
/*use Twilio\Rest\Client; 
$to="+919659929960";
$msg='TEST';
class Twilio
{
function sendotp($to,$msg)
{
	
 echo "<pre>"; print_r(array($to,$msg)); echo "</pre>"; //exit;sendotp_whatsapp
require_once 'Twilio/autoload.php'; 

//$sid    = "AC2792b1671f6922f9cf17dbe56ac0107b"; 
$sid    = "AC0cb00021d7054df8989f004397adf52c";
//$token  = "1cef381629e66c4a8d44add723fdb4a1"; 
$token  = "af9b554af14f1ad8d3ecbcaf4778f01e"; 

$twilio = new Client($sid, $token); 
 

	$message = $twilio->messages 
                  ->create($to, // to 
                           array(  
                               "from" => "+16194854852",
							   //"messagingServiceSid" => "SMa7635f97f9434c3b8d142b21b7988cb7",      
                               "body" => $msg 
                           ) 
                  ); 
				  print($message->sid); exit;
	return $message->sid;
}

function whatsapp($msg,$to)
{
	$to='+919659929960';
require_once 'Twilio/autoload.php'; 
$sid    = "AC2792b1671f6922f9cf17dbe56ac0107b"; 
$token  = "1cef381629e66c4a8d44add723fdb4a1"; 
$twilio = new Client($sid, $token);

$message = $twilio->messages
                  ->create("whatsapp:".$to, // to
                           array(
                               "from" => "whatsapp:+14155238886",
                               "body" => "Hi Joe! Thanks for placing an order with us. We’ll let you know once your order has been processed and delivered. Your order number is O12235234"
                           )
                  );

print($message);
}
}*/

require_once 'Twilio/autoload.php';

use Twilio\Rest\Client;

function sendotp_old($to, $msg)
{
    $sid    = "AC87fe6c76cdfcb3dba5034f623690b040";
    $token  = "5076c56d95cd4cb40a7a0e9cd4f14ca6";
    //	$sid    = "";
    //	$token  = "";
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
        ->create(
            $to, // to
            array(
                "body" => $msg, "from" => "+60146482623"
            )
        );

    //print($message); exit;
    return $message->sid;
}
function sendotp($t_sid, $t_token, $t_messagingServiceSid, $mobile, $msg)
{

    $sid    = $t_sid;
    $token  = $t_token;
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
        ->create(
            "$mobile", // to 
            array(
                "messagingServiceSid" => "$t_messagingServiceSid",
                "body" => "$msg"
            )
        );

    //	print($message->sid);
    //	print($message); exit;
    //	return $message->sid;
}
function sendotp_whatsapp($t_sid, $t_token, $t_messagingServiceSid, $mobile, $msg)
{
    /* echo $t_sid;
    echo "</br>".$t_token;
    echo "</br>".$t_messagingServiceSid;
    echo "</br>".$mobile;
    echo "</br>".$msg; exit; */

    $sid    = $t_sid;
    $token  = $t_token;
    $twilio = new Client($sid, $token);
try{
    $message = $twilio->messages
        ->create(
            "whatsapp:$mobile", // to 
            array(
                "from" => "whatsapp:$t_messagingServiceSid",
                "body" => "$msg"
            )
        );}catch(\Twilio\Exceptions\RestException $e){
            echo $e->getCode() . ' : ' . $e->getMessage()."<br>";
          }
    // read the last message status
    sleep(1);
    $lastRecord = $twilio->messages->read([], 1, 2)[0];
    
    print_r($lastRecord);
    $data['status'] = $lastRecord->status;
    $data['errorCode'] = $lastRecord->errorCode;
    return $data;
}

function sendotp_static($t_sid, $t_token, $t_messagingServiceSid, $mobile, $msg)
{
    //echo "yes".$msg; 
    //$sid    = $t_sid; 
    //$token  = $t_token; 
    $sid    = "ACee2c631b4ec665941d34c9af2f50de93";
    $token  = "762773fd21e6f653e87e9b8aa92b4dc3";
    $t_messagingServiceSid = "MG56f616e02ca64f6b8698004c9aafef2c";
    $mobile = "+919629839594";
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
        ->create(
            "+919629839594", // to 
            array(
                "messagingServiceSid" => "MG56f616e02ca64f6b8698004c9aafef2c",
                "body" => "Your message"
            )
        );

    print($message->sid);
    exit;
    //	print($message); exit;
    return $message->sid;
}
