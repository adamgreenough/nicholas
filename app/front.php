<?php
	
function load_theme($themeName) {
	require 'themes/' . $themeName . '/functions.php';
	
	global $router;
	
	$router->map('GET','/', function() { 
		$posts = get_posts();
		require 'themes/' . FRONTEND_THEME . '/home.php';
	}, 'home');
	
	$router->map('GET','/tag/[:tag]/', function($tag) { 
		$posts = get_posts(1, POSTS_PER_PAGE, $tag);
		require 'themes/' . FRONTEND_THEME . '/tag.php';
	});
	
	// Must be last to ensure other routes get detected first
	$router->map('GET','/[:slug]/', function($slug) { 
		$post = get_single($slug);
		require 'themes/' . FRONTEND_THEME . '/single.php';
	});
}

function get_header($title = BLOG_NAME, $description = BLOG_DESCRIPTION) {
	require 'themes/' . FRONTEND_THEME . '/header.php';
}

function get_footer() {
	require 'themes/' . FRONTEND_THEME . '/footer.php';
}

function display_tag_list($tags) {
	foreach ($tags as $tag) {
		$output .= 	'<a href="/' . BASE_URL . 'tag/' . strtolower($tag) . '/" title="Posts tagged ' . $tag . '">' . $tag . '</a>, ';
	}
	
	$output = rtrim($output, ', ');
	return $output;
}

function get_theme_directory_url() {
	return '/' . BASE_URL . 'themes/' . FRONTEND_THEME;
}