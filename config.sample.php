<?php

ini_set('display_errors', true);
ini_set('error_reporting', E_ALL ^ E_NOTICE);
ini_set('date.timezone', "Asia/Tokyo");

$config = array(
	'db' => array(
		'database' => 'calendar',
		'host' => 'localhost',
		'user' => 'user',
		'password' => 'password',
	),
);
