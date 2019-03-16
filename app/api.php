<?php
	
function api_feed() {
	if(isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}
	
	if(isset($_GET['perpage'])) {
		$perPage = $_GET['perpage'];
	} else {
		$perPage = POSTS_PER_PAGE;
	}
	
	if(isset($_GET['tag'])) {
		$tag = $_GET['tag'];
	} else {
		$tag = null;
	}
	
	return get_posts($page, $perPage, $tag);
}

function api_single() {
	if(isset($_GET['slug'])) {
		$slug = $_GET['slug'];
	} else {
		$slug = null;
	}
	
	return get_single($slug);
}