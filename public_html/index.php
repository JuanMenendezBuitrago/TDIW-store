<?php
require_once(dirname(__FILE__)."/protected/userID.php");
session_start();

// set user ID to guest if none is set
!isset($_SESSION['user']) && $_SESSION['user'] = UserID::newGuest();

// save config and PDO in GLOBALS
$GLOBALS['config'] = require(dirname(__FILE__)."/protected/config/main.php");
$GLOBALS['pdo'] = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8',$config['db']['host'], $config['db']['dbname']), $config['db']['dbuser'], $config['db']['password']);


function t($resource) {
	$config = $GLOBALS['config'];
	if(array_key_exists($resource, $config['resources'])) {
		return $config['resources'][$resource];
	}
	return($resource);
}

/*****************************
 process request
 *****************************/

// get verb
$GLOBALS['request_method'] = $verb = strtoupper($_SERVER['REQUEST_METHOD']);

// get URI without protocol and host info
if(isset($_SERVER['REQUEST_URI'])) {
	$requestUri = $_SERVER['REQUEST_URI'];
	if(!empty($_SERVER['HTTP_HOST'])) {
		if(strpos($requestUri,$_SERVER['HTTP_HOST']) !== false) {
			$requestUri = preg_replace('/^\w+:\/\/[^\/]+/','',$requestUri);
		}
	}
	else {
		$requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i','',$requestUri);
	}
}
// $requestUri = preg_replace('/^~tdiw-j3/i','',$requestUri);
// trim slashes from both ends of the string
$requestUri = trim($requestUri,'/');

// default route
$route = "index/index"; 
switch($verb) {
	case "GET":
		/**
		 * custom
		 */
		if(preg_match('/^$/', $requestUri)) { 
			$route = "product/index"; 
		}elseif(preg_match('/^registrat$/', $requestUri)) { 
			$route = "user/new"; 
		}elseif(preg_match('/^admin\/new$/', $requestUri)) { 
			$route = "admin/new"; 
		}elseif(preg_match('/^logout$/', $requestUri)) { 
			$route = "user/logout"; 
		}elseif(preg_match('/^cart$/', $requestUri)) { 
			$route = "order/cart"; 
		}elseif(preg_match('/^cistell$/', $requestUri)) { 
			$route = "user/cart"; 
		}elseif(preg_match('/^producte\/(?P<alias>[a-z\-]+)$/', $requestUri, $matches)) { 
			$route = "product/index/alias/{$matches['alias']}/ajax/1";
		}
		/**
		 * admin
		 */
		elseif(preg_match('/^admin\/(?P<resource>admin|usuari|categoria|comanda|producte)$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/admin";
		}elseif(preg_match('/^admin\/(?P<resource>admin|usuari|categoria|comanda|producte)\/list$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/list"; 
		}elseif(preg_match('/^admin\/(?P<resource>categoria|producte|usuari|admin)\/new$/', $requestUri, $matches)) {
			$route = t($matches['resource'])."/new"; 
		}elseif(preg_match('/^admin\/(?P<resource>admin|usuari|categoria|comanda|producte)\/(?P<id>\d+)$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/edit/id/".$matches['id']; 
		}elseif(preg_match('/^admin\/show\/(?P<resource>comanda|producte)\/(?P<id>\d+)$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/show/admin/1/id/".$matches['id']; 
		}
		// ---------------------------------------------------------------------------------------------
		// REST
		// ---------------------------------------------------------------------------------------------
		elseif(preg_match('/^(?P<resource>comandes|productes)$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/index"; 
		}elseif(preg_match('/^(?P<resource>usuari|comanda|producte|categoria|admin)\/(?P<id>\d+)$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/show/id/".$matches['id']; 
		}elseif(preg_match('/^(?P<resource>comanda)\/list\/(?P<id>\d+)$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/list/user_id/".$matches['id']; 
		}
		// captura el resto de los GET (siempre al final) --------------------------------------------------------------
		elseif(preg_match('/^(?P<controller>\w+)(\/(?P<action>\w+)(?P<parameters>\/.*)?)?$/', $requestUri, $matches)) {
			$action = isset($matches['action'])?$matches['action']:'index';
			$params = isset($matches['parameters'])?$matches['parameters']:'';
			$route = $matches['controller']."/".$action.$params;
		}
		break;
	case "PUT":
		if(preg_match('/^comanda\/(?P<action>inc|dec)\/(?P<product_id>\d+)$/', $requestUri, $matches)) { 
			$route = "order/update/product/{$matches['product_id']}/action/{$matches['action']}"; 
		}elseif(preg_match('/^comanda\/(?P<action>buida)$/', $requestUri, $matches)) { 
			$route = "order/update/action/{$matches['action']}"; 
		}elseif(preg_match('/^comanda\/(?P<action>checkout)$/', $requestUri, $matches)) { 
			$route = "order/create"; 
		}elseif(preg_match('/^comanda\/(?P<amount>\d+)\/(?P<product_id>\d+)$/', $requestUri, $matches)) { 
			$route = "order/update/product/{$matches['product_id']}/amount/{$matches['amount']}"; 
		}
		// ----------------------------------------------------------------------------------------------
		// REST
		// ----------------------------------------------------------------------------------------------
		elseif(preg_match('/^(?P<resource>categoria|comanda|producte|usuari|admin)\/(?P<id>\d+)$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/update/id/".$matches['id']; 
		}
		break;
	case "POST":
		if(preg_match('/^login$/', $requestUri)) { 
			$route ="user/login"; 
		}elseif(preg_match('/^upload$/', $requestUri)) { 
			$route ="product/upload"; 
		}
		// ---------------------------------------------------------------------------------------------
		// REST
		// ---------------------------------------------------------------------------------------------
		elseif(preg_match('/^(?P<resource>admin|categoria|comanda|producte|usuari)$/', $requestUri, $matches)) { 
			$route = t($matches['resource'])."/create"; 
		}
		break;
	case "DELETE":
		// ---------------------------------------------------------------------------------------------
		// REST
		// ---------------------------------------------------------------------------------------------
		if(preg_match('/^(?P<resource>categoria|comanda|producte|usuari|admin)\/(?P<id>\d+)$/', $requestUri, $matches)) {
			$route = t($matches['resource'])."/delete/id/".$matches['id']; 
		}
		break;
	default:
}

preg_match('/^(\w+)\/(\w+)\/?(.*)$/', $route, $matches);

// turn route into controller + action
$controller = ucfirst($matches[1])."Controller";
$action = "action".ucfirst($matches[2]);

$params = [];
if($matches[3] != '') 
{
	$split = preg_split('/\//', $matches[3],-1,PREG_SPLIT_NO_EMPTY);
	for($i = 0; $i < sizeof($split) / 2; $i++)
	{
		$params[$split[$i*2]] = $split[$i*2+1];
	}
}
// var_dump($controller, $action, $params); exit();
require_once(dirname(__FILE__)."/protected/controllers/".lcfirst($controller).".php");
$controller = new $controller();
$controller->$action($params);

