<?php
/**
 * GET /api/regions get region list
 * GET /api/waterbases get waterbases list
 * 
 * POST /api/order order some water
 */
require 'lib/controller.php';
require 'lib/order.php';

use \Lib\Controller;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// allowed cases in $routes, everything else results in a 404 Not Found
$routes = ['regions', 'waterbases', 'order'];
$route = trim($uri[2]);

if (!in_array($route, $routes)) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new Controller($route, $requestMethod);
$controller->request();