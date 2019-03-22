<?php
require_once 'vendor/autoload.php';
require_once 'config.php';
require_once 'app/posts.php';
require_once 'app/plugins.php';
require_once 'app/generate.php';
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

if(!USE_FRONTEND) {
	$router->map('GET','/', function() { 
		require 'views/default.php';
	});
} else {
	require_once 'app/frontend.php';
	require_once 'themes/' . FRONTEND_THEME . '/functions.php';
	
	$router->map('GET','/tag/[:tag]/[i:page]?/', function($tag, $page = 1) { 
		$posts = get_posts($page, POSTS_PER_PAGE, $tag);
		$tag = str_replace('%20', ' ', $tag);
		
		if($posts) {
			require 'themes/' . FRONTEND_THEME . '/tag.php';
		} else {
			header("HTTP/1.0 404 Not Found");
			require 'views/404.php';		
		}
	});
	
	$router->map('GET','/[i:page]?/', function($page = 1) { 
		$posts = get_posts($page);

		require 'themes/' . FRONTEND_THEME . '/home.php';
	}, 'home');
	
	// Must be last to ensure other routes get detected first
	$router->map('GET','/[:slug]/', function($slug) { 
		$post = get_single($slug);
		if($post->title) {
			require 'themes/' . FRONTEND_THEME . '/single.php';
		} else {
			header("HTTP/1.0 404 Not Found");
			require 'views/404.php';		
		}
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