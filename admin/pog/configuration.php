<?php
/*if (!isset($_SESSION))
{
	session_start();
}*/
global $configuration;
$configuration['soap'] = "http://www.phpobjectgenerator.com/services/soap.php?wsdl";
$configuration['homepage'] = "http://www.phpobjectgenerator.com";
$configuration['revisionNumber'] = "";
$configuration['versionNumber'] = "2.6";

$configuration['setup_password'] = '';

//db_encoding=1 is highly recommended unless you know what you're doing
$configuration['db_encoding'] = 1;

// edit the information below to match your database settings

$configuration['db']	= 'salus'; 		//	database name
$configuration['host']	= 'localhost';	//	database host
$configuration['user']	= 'salus';		//	database user
$configuration['pass']	= 't3h_salus_test';		//	database password
$configuration['port'] 	= 'mysql';		//	database port
?>
