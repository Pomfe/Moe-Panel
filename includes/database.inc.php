<?php
require_once 'settings.inc.php';

//$db = new PDO(MOE_DB_CONN, MOE_DB_USER, MOE_DB_PASS);
try {
	$db = new PDO("mysql:host=" . MOE_DB_IP . ";dbname=". MOE_DB_NAME, MOE_DB_USER, MOE_DB_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
} catch (PDOException $e) {
	echo $e;
}