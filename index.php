<?php
require 'vendor/autoload.php';
require 'config.php';
require 'app/functions.php';

$router = new AltoRouter();
$router->setBasePath(BASE_URL);

/* 
	Load Plugins
*/

load_plugins();

$router->map('GET','/info/', function() { 
	require 'views/info.php';
});

/* 
	API Routes 
*/

$router->map('GET','/api/', function() { 
	header('Content-type: application/json');
	echo generate_json(get_posts());
});

/* 
	Front-end Routes 
*/

if(USE_FRONTEND) {
	load_theme(FRONTEND_THEME);
} else {
	$router->map('GET','/', function() { 
		echo 'Front-end off';
	});
}

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