<?php

@session_start();

@ob_start();

date_default_timezone_set('Asia/Calcutta'); 

ini_set('display_errors',0);

ini_set('max_execution_time', 300);



ini_set('session.auto_start','Off');

header('Cache-control: private');

session_set_cookie_params(7200); 

date_default_timezone_set("Asia/Kolkata"); 

$_SESSION['system']['pro-mac-id'];

$_SESSION['system']['current_time'] = time();

$_SESSION['system']['current_date'] = date('d/m/Y h:i:s A',$_SESSION['system']['current_time']);

if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet

    {

       $ip=$_SERVER['HTTP_CLIENT_IP'];

    }

    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy

    {

      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];

    }

    else

    {

      $ip=$_SERVER['REMOTE_ADDR'];

    }

	function Encrypt($password, $data)

{



	$salt = substr(md5(mt_rand(), true), 8);



	$key = md5($password . $salt, true);

	$iv  = md5($key . $password . $salt, true);



	$ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);



	return base64_encode('Salted__' . $salt . $ct);

}

function Decrypt($password, $data)

{



	$data = base64_decode($data);

	$salt = substr($data, 8, 8);

	$ct   = substr($data, 16);



	$key = md5($password . $salt, true);

	$iv  = md5($key . $password . $salt, true);



	$pt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ct, MCRYPT_MODE_CBC, $iv);



	return $pt;

}

$ip=$_SERVER['REMOTE_ADDR'];

system('ipconfig/all'); $mycom=ob_get_contents(); ob_clean(); $findme = "Physical"; $pmac = strpos($mycom, $findme); $mac=substr($mycom,($pmac+36),17); $_SESSION['system']['pro_mac_id'] = $mac;

try {

if($_SERVER['DOCUMENT_ROOT']=='D:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='D:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampp/htdocs') { 

	$con = new PDO('mysql:host=localhost;dbname=beemo','root','');

	}

	else 

	{

	$con = new PDO('mysql:host=localhost;dbname=paytm-clone_paytm-clone', 'paytm-clone_paytm-clone', 'paytm-clone#1416');

	}

	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    

} catch (PDOException $e) {

	header('location:server-maintenance.php');

}



function converCurrency($from,$to,$amount){

	/*$url = "http://www.google.com/finance/converter?a=$amount&from=$from&to=$to"; 

	$request = curl_init(); 

	$timeOut = 0; 

	curl_setopt ($request, CURLOPT_URL, $url); 

	curl_setopt ($request, CURLOPT_RETURNTRANSFER, 1); 

	curl_setopt ($request, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); 

	curl_setopt ($request, CURLOPT_CONNECTTIMEOUT, $timeOut); 

	$response = curl_exec($request); 

	curl_close($request);

	$regularExpression     = '#\<span class=bld\>(.+?)\<\/span\>#s';

    preg_match($regularExpression, $response, $finalData);

	//$data=explode(' ',$finalData[0]);

	if(isset($finalData[0]))

    //return round($data[0]).' '.$data[1]; 

	return $finalData[0];*/

	//return '$ '.round($amount/64.50);

	return '&#8377; '.round($amount);

} 

$_SESSION['system']['log']['pinfo'] = 'GATEWAY_INTERFACE : '.$_SERVER['GATEWAY_INTERFACE'].'<br/>'.'HTTP_ACCEPT : '.$_SERVER['HTTP_ACCEPT'].'<br/>'.'HTTP_ACCEPT_ENCODING : '.$_SERVER['HTTP_ACCEPT_ENCODING'].'<br/>'.'HTTP_ACCEPT_LANGUAGE : '.$_SERVER['HTTP_ACCEPT_LANGUAGE'].'<br/>'.'HTTP_CONNECTION : '.$_SERVER['HTTP_CONNECTION'].'<br/>'.'HTTP_HOST : '.$_SERVER['HTTP_HOST'].'<br/>'.'HTTP_USER_AGENT : '.$_SERVER['HTTP_USER_AGENT'].'<br/>'.'QUERY_STRING : '.$_SERVER['QUERY_STRING'].'<br/>'.'REMOTE_ADDR : '.$_SERVER['REMOTE_ADDR'].'<br/>'.'REMOTE_PORT : '.$_SERVER['REMOTE_PORT'].'<br/>'.'REQUEST_METHOD : '.$_SERVER['REQUEST_METHOD'].'<br/>'.'SCRIPT_FILENAME : '.$_SERVER['SCRIPT_FILENAME'].'<br/>'.'SERVER_NAME : '.$_SERVER['SERVER_NAME'].'<br/>'.'SERVER_ADMIN : '.$_SERVER['SERVER_ADMIN'].'<br/>'.'SERVER_PROTOCOL : '.$_SERVER['SERVER_PROTOCOL'].'<br/>'.'SERVER_SIGNATURE : '.$_SERVER['SERVER_SIGNATURE'].'<br/>'.'SERVER_SOFTWARE : '.$_SERVER['SERVER_SOFTWARE'];



?>

 

