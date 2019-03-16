<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'app/functions.php';
require_once 'app/api.php';

$router = new AltoRouter();
$router->setBasePath(BASE_URL);

/* ============================================
   Plugins
 ============================================ */

load_plugins();

$router->map('GET','/info/', function() { 
	require 'views/info.php';
});

/* ============================================
   Subscription Feeds
 ============================================ */

$router->map('GET','/json/', function() { 
	header('Content-type: application/json');
	echo generate_json(get_posts());
});

$router->map('GET','/rss/', function() { 
	header('Content-type: application/json');
	echo generate_rss(get_posts());
});

/* ============================================
   Front-end
 ============================================ */

if(USE_FRONTEND) {
	require 'app/frontend.php';
	load_theme(FRONTEND_THEME);
} else {
	$router->map('GET','/', function() { 
		require 'views/default.php';
	});
}

/* ============================================
   API
 ============================================ */

$router->map('GET','/api/feed/', function() { 
	header('Content-type: application/json');
	echo generate_json(api_feed());
});

$router->map('GET','/api/single/', function() { 
	header('Content-type: application/json');
	echo generate_json(api_single());
});

/* ============================================
   Matching
 ============================================ */

$match = $router->match();

if($match) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	header("HTTP/1.0 404 Not Found");
	require 'views/404.php';
}