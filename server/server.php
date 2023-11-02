<?php
@session_start();
@ob_start();
date_default_timezone_set('America/Belize'); 
setlocale(LC_MONETARY,'English_Belize');
ini_set('display_errors',1);
ini_set('memory_limit',-1);
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
*/
session_set_cookie_params(7200);
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
$ip=$_SERVER['REMOTE_ADDR'];
system('ipconfig/all'); $mycom=ob_get_contents(); ob_clean(); $findme = "Physical"; $pmac = strpos($mycom, $findme); $mac=substr($mycom,($pmac+36),17); $_SESSION['system']['pro_mac_id'] = $mac;
try {
if($_SERVER['DOCUMENT_ROOT']=='D:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='D:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT']=='E:/xampp1/htdocs') { 
	$con = new PDO('mysql:host=localhost;dbname=shop_from_asia','root','');
	}
	else 
	{
	$con = new PDO('mysql:host=localhost;dbname=doditorg_latestshop', 'doditorg_latestshop', 'AQ)^2OIIlg6I');
	}
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	//header('location:server-maintenance.php');
}
?>