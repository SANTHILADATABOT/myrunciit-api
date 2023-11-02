<?php
@session_start();
@ob_start();
date_default_timezone_set('Asia/Calcutta'); 
ini_set('display_errors',1);
ini_set('max_execution_time', 300);
ini_set('session.auto_start','Off');
header('Cache-control: private');

/*ini_set('expose_php','Off');
ini_set('error_reporting','E_ALL');
ini_set('display_errors','Off');
ini_set('display_startup_errors','Off');
ini_set('enable_dl','On');
ini_set('disable_functions','system, exec, shell_exec, passthru, phpinfo, show_source, popen, proc_open');
ini_set('disable_functions','fopen_with_path,dbmopen,dbase_open,putenv,move_uploaded_file');
ini_set('disable_functions','chdir, mkdir, rmdir, chmod, rename');
ini_set('disable_functions','filepro, filepro_rowcount, filepro_retrieve, posix_mkfifo');
session_set_cookie_params(7200);
date_default_timezone_set("Asia/Kolkata"); */
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
if($_SERVER['DOCUMENT_ROOT']=='D:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='D:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT']=='E:/xampp1/htdocs') { 
	$con = new PDO('mysql:host=localhost;dbname=oyabuy','root','');
	}
	else 
	{
	$con = new PDO('mysql:host=localhost;dbname=oyabuy_oya', 'oyabuy_oya', 'hUTd&!@p%Hbj');
	}
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	header('location:server-maintenance.php');
}
$_SESSION['system']['log']['pinfo'] = 'GATEWAY_INTERFACE : '.$_SERVER['GATEWAY_INTERFACE'].'<br/>'.'HTTP_ACCEPT : '.$_SERVER['HTTP_ACCEPT'].'<br/>'.'HTTP_ACCEPT_ENCODING : '.$_SERVER['HTTP_ACCEPT_ENCODING'].'<br/>'.'HTTP_ACCEPT_LANGUAGE : '.$_SERVER['HTTP_ACCEPT_LANGUAGE'].'<br/>'.'HTTP_CONNECTION : '.$_SERVER['HTTP_CONNECTION'].'<br/>'.'HTTP_HOST : '.$_SERVER['HTTP_HOST'].'<br/>'.'HTTP_USER_AGENT : '.$_SERVER['HTTP_USER_AGENT'].'<br/>'.'QUERY_STRING : '.$_SERVER['QUERY_STRING'].'<br/>'.'REMOTE_ADDR : '.$_SERVER['REMOTE_ADDR'].'<br/>'.'REMOTE_PORT : '.$_SERVER['REMOTE_PORT'].'<br/>'.'REQUEST_METHOD : '.$_SERVER['REQUEST_METHOD'].'<br/>'.'SCRIPT_FILENAME : '.$_SERVER['SCRIPT_FILENAME'].'<br/>'.'SERVER_NAME : '.$_SERVER['SERVER_NAME'].'<br/>'.'SERVER_ADMIN : '.$_SERVER['SERVER_ADMIN'].'<br/>'.'SERVER_PROTOCOL : '.$_SERVER['SERVER_PROTOCOL'].'<br/>'.'SERVER_SIGNATURE : '.$_SERVER['SERVER_SIGNATURE'].'<br/>'.'SERVER_SOFTWARE : '.$_SERVER['SERVER_SOFTWARE'];
?>