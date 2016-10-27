<?php
session_start();
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

$route = "index/index"; 
switch($verb)
{
	case "GET":
		if(preg_match('/^\/$/', $requestUri)) { 
			$route = "index/index"; 
		}
		if(preg_match('/^\/registrat$/', $requestUri)) 	{ 
			$route = "user/register"; 
		}
		if(preg_match('/^\/producte\/(?P<category>\w)$/', $requestUri, $matches)) { 
			$route = "product/list/category/{$matches['category']}";
		}
		break;
	case "PUT":
		if(preg_match('/^\/comanda\/(?P<action>inc|dec)\/(?P<order_id>\d+)\/(?P<product_id>\d+)$/', $requestUri, $matches)) {
			$route = "order/update/order/{$matches['order_id']}/product/{$matches['product_id']}/action/{$matches['action']}";
		}
		if(preg_match('/^\/comanda\/(?P<amount>\d+)\/(?P<order_id>\d+)\/(?P<product_id>\d+)$/', $requestUri, $matches)) {
			$route = "order/update/order/{$matches['order_id']}/product/{$matches['product_id']}/amount/{$matches['amount']}";
		}
		break;
	case "POST":
		if(preg_match('/^\/usuari$/', $requestUri)) { 
			$route = 'user/register'; 
		}
		break;
	case "DELETE":
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

require_once(dirname(__FILE__)."/../protected/controllers/".lcfirst($controller).".php");
$controller = new $controller();
$controller->$action($params);