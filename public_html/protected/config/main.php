<?php 

return array(
	'max_file_size' => 1000000,
	'resources' => array(
		'producte' => 'product',
		'productes' => 'product',
		'usuari' => 'user',
		'usuaris' => 'user',
		'comanda' => 'order',
		'comandes' => 'order',
		'categoria' => 'category',
		'categories' => 'category',
		'proveidor' => 'supplier',
		'proveidors' => 'supplier',
		'admin' => 'admin',
		'admins' => 'admin',
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
	'pictures_path' => dirname(__FILE__)."/../../img/products",
	'layout'      => 'layout',
	
	// base_url es un prefijo necesario para construir las url de los enlaces
	//'base_url'  => 'tdiw-j3/', 	// Cuando trabajemos en deic
	//'host_url'	  => 'http://deic-dc0.uab.cat/',
	'base_url'    => '', 				// Cuando trabajemos en local
	'host_url'	  => 'http://etse.local/',
);