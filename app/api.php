<?php
	
function api_feed() {
	$page = $_GET['page'] ?? 1;
	$perPage = $_GET['perpage'] ?? $config['posts_per_page'];
	$tag = $_GET['tag'] ?? null;
	return get_posts($page, $perPage, $tag);
}

function api_single() {
	$slug = $_GET['slug'] ?? null;
	return get_single($slug);
}