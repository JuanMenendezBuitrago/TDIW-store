<?php 

return array(
	'db' => array(
		'charset' => 'utf8mb4_unicode_ci',
		'host' => 'localhost',
		'dbname' => 'tdiw-j3',
		// 'dbuser' => 'tdiw-j3',
		'dbuser' => 'root',
		// 'password' => 'aungohgh',
		'password' => 'root'
	),

	'view_path' => dirname(__FILE__)."/../views",
	
	'layout' => 'layout',
	'layout_path' => dirname(__FILE__)."/../views/layouts",

	'base_url' => '/',
);