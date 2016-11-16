<?php 

return array(
	'max_file_size' => 1000000,
	'resources' => array(
		'producte' => 'product',
		'productes' => 'products',
		'usuari' => 'user',
		'usuaris' => 'users',
		'comanda' => 'order',
		'comandes' => 'orders',
		'categoria' => 'category',
		'categories' => 'categories',
		'proveidor' => 'supplier',
		'proveidors' => 'suppliers',
		'admin' => 'admin',
		'admins' => 'admins',
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
	'pictures_path' => dirname(__FILE__)."/../../public_html/img/products",
	'layout'      => 'layout',
	
	// base_url es un prefijo necesario para construir las url de los enlaces
	//'base_url'  => 'tdiw-j3/', 	// Cuando trabajemos en deic
	'base_url'    => '', 				// Cuando trabajemos en local
);