<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$active_group = 'default';
$query_builder = TRUE;

if($_SERVER['DOCUMENT_ROOT']=='D:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='D:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT']=='E:/xampp 7.2/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampplite/htdocs' || $_SERVER['DOCUMENT_ROOT']=='C:/xampp/htdocs' || $_SERVER['DOCUMENT_ROOT']=='D:/xampp7.2.34/htdocs' || $_SERVER['DOCUMENT_ROOT']=='E:/Xampp7.2/htdocs') 
{ 
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'coscosof_shopping',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

}
else 
{
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	//'username' => 'betamyrun_db',
	//'password' => 'GM&mf_gHci;S',
	//'database' => 'betamyrun_db',
	'username' => 'betamyrun_t2',
	'password' => 'bSDO!-gl7FK=',
	'database' => 'betamyrun_t2',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => FALSE,
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

}