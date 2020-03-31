<?php 

//Note: This file should be included first in every php page.

define('BASE_PATH', dirname(dirname(__FILE__)));
define('APP_FOLDER','www_bookstore');
define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));


require_once BASE_PATH.'/db/MysqliDb.php';
require_once BASE_PATH.'/db/class.php';
//require_once "./includes/header.php";
/*
|--------------------------------------------------------------------------
| DATABASE CONFIGURATION
|--------------------------------------------------------------------------
*/

define('DB_HOST', "localhost");
define('DB_USER', "root");
define('DB_PASSWORD', "");
define('DB_NAME', "www_bookstore");

/**
* Get instance of DB object
*/
function getDbInstance()
{
	return new MysqliDb(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME); 
		
}
function getDataOperations()
{
	return new DataOperations(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME); 	
}

$con = new mysqli("localhost", "root", "" ,"www_bookstore")or die('connection error');
