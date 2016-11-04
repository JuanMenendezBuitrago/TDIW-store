<?php 

return array(
	'resources' => array(
		'producte' => 'product',
		'usuari' => 'user',
		),
	'db' => array(
		'charset'     => 'utf8mb4_unicode_ci',
		'host'        => 'localhost',
		'dbname'      => 'tdiw-j3',
		// 'dbuser'   => 'tdiw-j3',
		'dbuser'      => 'root',
		// 'password' => 'aungohgh',
		'password'    => 'root'
		),
		
	'view_path'   => dirname(__FILE__)."/../views",
	'layout_path' => dirname(__FILE__)."/../views/layouts",
	'layout'      => 'layout',
	
	// base_url es un prefijo necesario para construir las url de los enlaces
	//'base_url'  => 'tdiw-j3/', 	// Cuando trabajemos en deic
	'base_url'    => '', 				// Cuando trabajemos en local
);