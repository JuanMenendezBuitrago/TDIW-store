<?php
session_start();
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
$verb = strtoupper($_SERVER['REQUEST_METHOD']);

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
$requestUri = trim($requestUri,'/');
$route = "index/index"; 
switch($verb)
{
	case "GET":
		// captura GET ""
		if(preg_match('/^$/', $requestUri)) { 
			$route = "index/index"; 
		}
		// captura GET "registrat"
		elseif(preg_match('/^registrat/', $requestUri)) 	{ 
			$route = "user/register"; 
		}
		// captura GET "producte/<categoria>"
		elseif(preg_match('/^producte\/(?P<category>[a-zA-Z]+)$/', $requestUri, $matches)) { 
			$route = "product/list/category/{$matches['category']}";
		}
		// 
		// REST
		// 
		elseif(preg_match('/^(?P<resource>\w+)$/', $requestUri, $matches)){
			$route = t($matches['resource'])."/list";
		}
		elseif(preg_match('/^(?P<resource>\w+)\/(?P<id>\d+)$/', $requestUri, $matches)){
			$route = t($matches['resource'])."/view/id/".$matches['id'];
		}
		// 
		// 
		// captura el resto de los GET (siempre al final)
		// 
		// 
		elseif(preg_match('/^(?P<controller>\w+)(\/(?P<action>\w+)(?P<parameters>\/.*)?)?$/', $requestUri, $matches)) {
			$action = isset($matches['action'])?$matches['action']:'index';
			$params = isset($matches['parameters'])?$matches['parameters']:'';
			$route = $matches['controller']."/".$action.$params;
		}
		break;
	case "PUT":
		if(preg_match('/^\/comanda\/(?P<action>inc|dec)\/(?P<order_id>\d+)\/(?P<product_id>\d+)$/', $requestUri, $matches)) {
			$route = "order/update/order/{$matches['order_id']}/product/{$matches['product_id']}/action/{$matches['action']}";
		}
		elseif(preg_match('/^\/comanda\/(?P<amount>\d+)\/(?P<order_id>\d+)\/(?P<product_id>\d+)$/', $requestUri, $matches)) {
			$route = "order/update/order/{$matches['order_id']}/product/{$matches['product_id']}/amount/{$matches['amount']}";
		}
		// 
		// REST
		// 
		elseif(preg_match('/^(?P<resource>\w+)\/(?P<id>\d+)$/', $requestUri, $matches)){
			$route = t($matches['resource'])."/update/id/".$matches['id'];
		}
		break;
	case "POST":
		// 
		// REST
		// 
		if(preg_match('/^(?P<resource>\w+)$/', $requestUri, $matches)){
			$route = t($matches['resource'])."/create";
		}
		break;
	case "DELETE":
		// 
		// REST
		// 
		if(preg_match('/^(?P<resource>\w+)\/(?P<id>\d+)$/', $requestUri, $matches)){
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
require_once(dirname(__FILE__)."/../protected/controllers/".lcfirst($controller).".php");
$controller = new $controller();
$controller->$action($params);

