<?php
require 'vendor/autoload.php';
require 'app/functions.php';

$router = new AltoRouter();
$router->setBasePath('/blog');

$router->map('GET','/', function() { 
	echo print_r(get_posts());
}, 'home');

$router->map('GET','/hello/', function() { 
	echo 'hello';
}, 'hello');

$router->map('GET','/api/', function() { 
	header('Content-type: application/json');
	echo generate_json(get_posts());
}, 'api');

$match = $router->match();

if($match) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	header("HTTP/1.0 404 Not Found");
	echo $router->match() . '404';
}