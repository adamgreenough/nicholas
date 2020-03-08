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
	
	return $config['base_url'] . '/themes/' . $config['frontend_theme'];
}

function get_header($title = null, $description = null) {
	$config = include('config.php'); 
	
	if($title == null) {
		$title = $config['blog_name'];
	} else {
		$title = $title . ' ' . $config['title_seperator'] . ' ' . $config['blog_name']; 
	}
	
	if($description == null) {
		$description = $config['blog_description'];
	}
	
	require 'themes/' . $config['frontend_theme'] . '/header.php';
}

function get_footer() {
	$config = include('config.php'); 
	
	require 'themes/' . $config['frontend_theme'] . '/footer.php';
}

function get_post_link($post) {
	$config = include('config.php'); 
	$post_base = '';
	
	if($config['post_base']) {
		$post_base = date('Y/m', $post->date) . '/';
	}
	
	return $config['base_url']  . '/' . $post_base . $post->slug . '/';
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
		$pagination['next'] = $config['base_url'] . '/' . $tag . ($page + 1) . '/';
	}
	
	if($page > 1) {
		$pagination['prev'] = $config['base_url'] . '/' . $tag . ($page - 1) . '/';
	}
	
	return $pagination;
}