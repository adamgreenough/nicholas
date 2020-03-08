<?php
require_once 'vendor/autoload.php';
require_once 'app/posts.php';
require_once 'app/pages.php';
require_once 'app/plugins.php';
require_once 'app/generate.php';
require_once 'app/api.php';

$config = include('config.php');

$router = new AltoRouter();
$router->setBasePath($config['base_url']);

/* ============================================
   Plugins
 ============================================ */

load_plugins();

/* ============================================
   Subscription Feeds
 ============================================ */

$router->map('GET','/json/', function() { 
	header('Content-type: application/json');
	echo generate_json(get_posts());
});

$router->map('GET','/rss/', function() { 
	header('Content-type: application/xml');
	echo generate_rss(get_posts());
});

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
   Front-end
 ============================================ */

// If the front-end option in config is set to false, skip the loading of frontend functionality
if(!$config['use_frontend']) {
	$router->map('GET','/', function() { 
		require 'views/default.php';
	});
} else {
	require_once 'app/frontend.php';
	require_once 'themes/' . $config['frontend_theme'] . '/functions.php';
	
	$router->map('GET','/tag/[:tag]/[i:page]?/', function($tag, $page = 1) {
		$config = include('config.php'); 
		$posts = get_posts($page, $config['posts_per_page'], $tag);
		$tag = str_replace('%20', ' ', $tag);
		
		if($posts) {
			include 'themes/' . $config['frontend_theme'] . '/tag.php';
		} else {
			error_404();
		}
	});
	
	$router->map('GET','/[i:page]?/', function($page = 1) {
		$config = include('config.php'); 
		$posts = get_posts($page);

		if($posts) {
			include 'themes/' . $config['frontend_theme'] . '/home.php';
		} else {
			error_404();
		}
	});
	
	$router->map('GET','/page/[:page]/', function($page) { 
		$config = include('config.php');
		$page = get_page($page);
		if($page->title) {
			include 'themes/' . $config['frontend_theme'] . '/page.php';
		} else {
			error_404();
		}
	});

	// Must be last to ensure other routes get detected first
	if($config['post_base']) {
		$router->map('GET','/[:year]/[:month]/[:slug]/', function($year, $month, $slug) { 
			$config = include('config.php');
			$post = get_single($slug, $year, $month);
			if($post->title) {
				include 'themes/' . $config['frontend_theme'] . '/single.php';
			} else {
				error_404();	
			}
		});
	}
	else {	
		$router->map('GET','/[:slug]/', function($slug) { 
			$config = include('config.php');
			$post = get_single($slug);
			if($post->title) {
				include 'themes/' . $config['frontend_theme'] . '/single.php';
			} else {
				error_404();	
			}
		});	
	}
}

/* ============================================
   Matching
 ============================================ */

$match = $router->match();

if($match) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	error_404();
}