<?php

function error_404() {
	$config = include('config.php'); 	
	header("HTTP/1.0 404 Not Found");
	
	// If there's a custom 404 template in the theme, use that, if not, use default
	if($config['use_frontend'] && file_exists('themes/' . $config['frontend_theme'] . '/404.php')) {
		require 'themes/' . $config['frontend_theme'] . '/404.php';
	} else {
		require 'views/404.php';
	}
}

function get_theme_directory_url() {
	$config = include('config.php'); 
	
	return '/' . $config['base_url'] . 'themes/' . $config['frontend_theme'];
}

function get_header($title = null, $description = null) {
	$config = include('config.php'); 
	
	if($title == null) {
		$title = $config['blog_name'];
	}
	
	if($description == null) {
		$title = $config['blog_description'];
	}
	
	require 'themes/' . $config['frontend_theme'] . '/header.php';
}

function get_footer() {
	$config = include('config.php'); 
	
	require 'themes/' . $config['frontend_theme'] . '/footer.php';
}

function get_pagination_link($page, $posts, $tag = '') {
	$config = include('config.php'); 
	
	if($tag) {
		$count = count(get_tag_list($tag));
		$tag = 'tag/' . $tag . '/';	
	} else {
		$count = count(get_post_list());
	}
	
	if(($count / $config['posts_per_page']) > $page) {
		$pagination['next'] = '/' . $tag . $config['base_url'] . ($page + 1) . '/';
	}
	
	if($page > 1) {
		$pagination['prev'] = '/' . $tag . $config['base_url'] . ($page - 1) . '/';
	}
	
	return $pagination;
}