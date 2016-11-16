<?php
require_once(dirname(__FILE__)."/../protected/userID.php");
session_start();

// set user ID to guest if none is set
!isset($_SESSION['user']) && $_SESSION['user'] = new UserID();

// get the cart from the cookies or set an empty cart
$cart = !isset($_COOKIE['cart'])?null:$_COOKIE['cart'];
$cart_items = json_decode($cart, true);
if ($cart_items === null) {
	$cart_items = json_decode("{}",true);
	setcookie('cart','{}',0);
}

$GLOBALS['cart_items'] = $cart_items;

// save config and PDO in GLOBALS
$GLOBALS['config'] = require(dirname(__FILE__)."/../protected/config/main.php");
$GLOBALS['pdo'] = new PDO(sprintf('mysql:host=%s;dbname=%s;charset=utf8',$config['db']['host'], $config['db']['dbname']), $config['db']['dbuser'], $config['db']['password']);


function t($resource) {
	$config = $GLOBALS['config'];
	if(array_key_exists($resource, $config['resources'])) {
		return $config['resources'][$resource];
	}
	
	throw new Exception("Error Processing Request", 1);
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
// trim slashes from both ends of the string
$requestUri = trim($requestUri,'/');

// default route
$route = "index/index"; 
switch($verb) {
	case "GET":
		// captura GET ""
		if(preg_match('/^$/', $requestUri)) { $route = "product/index"; }
		// captura GET "registrat"
		elseif(preg_match('/^registrat$/', $requestUri)) { $route = "user/create"; }
		// captura GET "admin"
		elseif(preg_match('/^admin$/', $requestUri)) { $route = "admin/create"; }
		// captura GET "producte/<categoria>"
		elseif(preg_match('/^producte\/(?P<alias>[a-z0-9\-]+)$/', $requestUri, $matches)) { $route = "product/index/alias/{$matches['alias']}"; }
		// captura  GET "admin/usuaris|categories|comandes|productes"
		elseif(preg_match('/^admin\/(?P<resource>admins|usuaris|categories|comandes|productes|proveidors)$/', $requestUri, $matches)) { $route = t($matches['resource']."/list/admin/1"); }
		// captura  GET "admin/usuari|categoria|comanda|producte"
		elseif(preg_match('/^admin\/(?P<resource>usuari|categoria|comanda|producte|proveidor)$/', $requestUri, $matches)) { $route = t($matches['resource'])."/create/admin/1"; }
		// captura  GET "admin/usuari|categoria|comanda|producte/<id>"
		elseif(preg_match('/^admin\/(?P<resource>admin|usuari|categoria|comanda|producte|proveidor)\/(?P<id>\d+)$/', $requestUri, $matches)) { $route = t($matches['resource'])."/show/id/".$matches['id']."/admin/1"; }
		// captura  GET "admin/usuari|categoria|comanda|producte/edit/<id>"
		elseif(preg_match('/^admin\/edita\/(?P<resource>admin|usuari|categoria|comanda|producte|proveidor)\/(?P<id>\d+)$/', $requestUri, $matches)) { $route = t($matches['resource'])."/update/id/".$matches['id']."/admin/1"; }
		// -------------------------------------------------------------------------------------------------------------
		// REST
		// -------------------------------------------------------------------------------------------------------------
		elseif(preg_match('/^(?P<resource>admin|categoria|comanda|producte|usuari|proveidor)$/', $requestUri, $matches)) { $route = t($matches['resource'])."/list"; }
		elseif(preg_match('/^(?P<resource>admin|categoria|comanda|producte|usuari|proveidor)\/(?P<id>\d+)$/', $requestUri, $matches)) { $route = t($matches['resource'])."/show/id/".$matches['id']; }
		// captura el resto de los GET (siempre al final) --------------------------------------------------------------
		elseif(preg_match('/^(?P<controller>\w+)(\/(?P<action>\w+)(?P<parameters>\/.*)?)?$/', $requestUri, $matches)) {
			$action = isset($matches['action'])?$matches['action']:'index';
			$params = isset($matches['parameters'])?$matches['parameters']:'';
			$route = $matches['controller']."/".$action.$params;
		}
		break;
	case "PUT":
		if(preg_match('/^\/comanda\/(?P<action>inc|dec)\/(?P<order_id>\d+)\/(?P<product_id>\d+)$/', $requestUri, $matches)) { $route = "order/update/order/{$matches['order_id']}/product/{$matches['product_id']}/action/{$matches['action']}"; }
		elseif(preg_match('/^\/comanda\/(?P<amount>\d+)\/(?P<order_id>\d+)\/(?P<product_id>\d+)$/', $requestUri, $matches)) { $route = "order/update/order/{$matches['order_id']}/product/{$matches['product_id']}/amount/{$matches['amount']}"; }
		// -------------------------------------------------------------------------------------------------------------
		// REST
		// -------------------------------------------------------------------------------------------------------------
		elseif(preg_match('/^(?P<resource>categoria|comanda|producte|usuari|proveidor)\/(?P<id>\d+)$/', $requestUri, $matches)) { $route = t($matches['resource'])."/update/id/".$matches['id']; }
		break;
	case "POST":
		if(preg_match('/^login$/', $requestUri)) { $route ="user/login"; }
		if(preg_match('/^upload$/', $requestUri)) { $route ="product/upload"; }
		// -------------------------------------------------------------------------------------------------------------
		// REST
		// -------------------------------------------------------------------------------------------------------------
		elseif(preg_match('/^(?P<resource>admin|categoria|comanda|producte|usuari|proveidor)$/', $requestUri, $matches)) { $route = t($matches['resource'])."/create"; }
		break;
	case "DELETE":
		// -------------------------------------------------------------------------------------------------------------
		// REST
		// -------------------------------------------------------------------------------------------------------------
		if(preg_match('/^(?P<resource>categoria|comanda|producte|usuari|proveidor)\/(?P<id>\d+)$/', $requestUri, $matches)) { $route = t($matches['resource'])."/delete/id/".$matches['id']; }
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
require_once(dirname(__FILE__)."/../protected/controllers/".lcfirst($controller).".php");
$controller = new $controller();
$controller->$action($params);

