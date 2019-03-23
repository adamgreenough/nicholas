<?php

function get_theme_directory_url() {
	return '/' . BASE_URL . 'themes/' . FRONTEND_THEME;
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

function get_next_page_link($page, $posts, $tag = '', $text = 'Next Page') {
	if($tag) {
		$count = count(get_tag_list($tag));
		$tag = 'tag/' . $tag . '/';	
	} else {
		$count = count(get_post_list());
	}
	
	if(($count / POSTS_PER_PAGE) > $page) {
		echo '<a href="/' . $tag . BASE_URL . ($page + 1) . '/" title="Next Page">' . $text . '</a>';
	}
}

function get_prev_page_link($page, $posts, $tag = '', $text = 'Previous Page') {
	if($tag) {
		$tag = 'tag/' . $tag . '/';
	}
	
	if($page > 1) {
		echo '<a href="/' . $tag . BASE_URL . ($page - 1) . '/" title="Previous Page">' . $text . '</a>';
	}
}