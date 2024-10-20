<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$start_time = microtime(true);

require_once 'vendor/autoload.php';
require_once 'app/core.php';
require_once 'app/posts.php';
require_once 'app/pages.php';
require_once 'app/plugins.php';
require_once 'app/generate.php';
require_once 'app/api.php';

$config = include('config.php');

$router = new AltoRouter();
$router->setBasePath($config['base_path']);

/* ============================================
   Plugins
 ============================================ */

load_plugins();

/* ============================================
   Subscription Feeds
 ============================================ */

$router->map('GET|HEAD','/json/', function() { 
	header('Content-type: application/json');
	echo generate_json(get_posts());
});

$router->map('GET|HEAD','/rss/', function() { 
	header('Content-type: application/xml');
	echo generate_rss(get_posts());
});

/* ============================================
   API
 ============================================ */

$router->map('GET|HEAD','/api/feed/', function() { 
	header('Content-type: application/json');
	echo generate_json(api_feed());
});

$router->map('GET|HEAD','/api/single/', function() { 
	header('Content-type: application/json');
	echo generate_json(api_single());
});

/* ============================================
   Front-end
 ============================================ */

// If the front-end option in config is set to false, skip the loading of frontend functionality
if(!$config['use_frontend']) {
	$router->map('GET|HEAD','/', function() { 
		require 'views/default.php';
	});
} else {
	require_once 'app/frontend.php';
	require_once 'themes/' . $config['frontend_theme'] . '/functions.php';
	
	$router->map('GET|HEAD','/tag/[:tag]/[i:page]?/', function($tag, $page = 1) {
		$config = include('config.php'); 
		$posts = get_posts($page, $config['posts_per_page'], $tag);
		$tag = str_replace('%20', ' ', $tag);
		
		if($posts) {
			include 'themes/' . $config['frontend_theme'] . '/tag.php';
		} else {
			error_404();
		}
	});
	
	$router->map('GET|HEAD','/[i:page]?/', function($page = 1) {
		$config = include('config.php'); 
		$posts = get_posts($page);

		if($posts) {
			include 'themes/' . $config['frontend_theme'] . '/home.php';
		} else {
			error_404();
		}
	});
	
	$router->map('GET|HEAD','/[:slug]/', function($slug) { 
		$config = include('config.php');

		// If post_base is not active, check posts by slug
		if(!$config['post_base']) {
			$post = get_single($slug);
			if(isset($post->title)) {
				include 'themes/' . $config['frontend_theme'] . '/single.php';
				return;
			}
		}

		// If no post was found search for page
		$page = get_page($slug);
		if($page->title) {
			include 'themes/' . $config['frontend_theme'] . '/page.php';
			return;
		}

		// When no post and no page was found, return error 404.
		error_404();
	});

	// Must be last to ensure other routes get detected first
	if($config['post_base']) {
		$router->map('GET|HEAD','/[:year]/[:month]/[:slug]/', function($year, $month, $slug) { 
			$config = include('config.php');
			$post = get_single($slug, $year, $month);
			if($post->title) {
				include 'themes/' . $config['frontend_theme'] . '/single.php';
			} else {
				error_404();	
			}
		});
	}
<<<<<<< HEAD
=======
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
>>>>>>> parent of 1451878 (Pesky tabs added back in)
}

/* ============================================
   Matching
 ============================================ */

$match = $router->match();

if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] );
} else {
	error_404();
}

