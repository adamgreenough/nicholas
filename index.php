<?php
require 'vendor/autoload.php';
require 'config.php';
require 'app/functions.php';

$router = new AltoRouter();
$router->setBasePath(BASE_URL);

$router->map('GET','/info', function() { 
	require 'views/info.php';
}, 'debug');

/* 
	API Routes 
*/

$router->map('GET','/api', function() { 
	header('Content-type: application/json');
	echo generate_json(get_posts());
}, 'api');

/* 
	Front-end Routes 
*/

if(USE_FRONTEND) {
	$router->map('GET','/', function() { 
		require 'themes/' . FRONTEND_THEME . '/index.php';
	}, 'home');
} else {
	$router->map('GET','/', function() { 
		echo 'Front-end off';
	}, 'home');
}

/* 
	Load Plugins
*/

load_plugins();

/* 
	Matching
*/

$match = $router->match();

if($match) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	header("HTTP/1.0 404 Not Found");
	require 'views/404.php';
}