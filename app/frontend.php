<?php
	
function load_theme($themeName) {
	require 'themes/' . $themeName . '/functions.php';
	
	global $router;
	
	$router->map('GET','/tag/[:tag]/[*:page]?/', function($tag, $page = 1) { 
		$posts = get_posts($page, POSTS_PER_PAGE, $tag);
		
		if($posts) {
			require 'themes/' . FRONTEND_THEME . '/tag.php';
		} else {
			header("HTTP/1.0 404 Not Found");
			require 'views/404.php';		
		}
	});
	
	$router->map('GET','/[*:page]?/', function($page = 1) { 
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

function get_next_page_link($page, $posts, $tag = '') {
	if($tag) {
		$count = count(get_tag_list($tag));
		$tag = 'tag/' . $tag . '/';	
	} else {
		$count = count(get_post_list());
	}
	
	if(($count / POSTS_PER_PAGE) > $page) {
		echo '<a href="/' . $tag . BASE_URL . ($page + 1) . '/">Next Page</a>';
	}
}

function get_prev_page_link($page, $posts, $tag = '') {
	if($tag) {
		$tag = 'tag/' . $tag . '/';
	}
	
	if($page > 1) {
		echo '<a href="/' . $tag . BASE_URL . ($page - 1) . '/">Prev Page</a>';
	}
}