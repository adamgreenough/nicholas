<?php

// Return list of all posts, newest first
function get_post_list($slug = '*', $date = '*') {
    static $_cache = array();

    if(empty($_cache)){
        $_cache = array_reverse(glob('posts/' . $date . '_' . $slug . '.md'));
    }

    return $_cache;
}

// Return an array of posts
function get_posts($page = 1, $perPage = POSTS_PER_PAGE) {
    $frontMatter = new Webuni\FrontMatter\FrontMatter();
    $posts = get_post_list();

    // Extract a specific page with results
    $posts = array_slice($posts, ($page - 1) * $perPage, $perPage);

    $tmp = array();

    foreach($posts as $k => $v) {
        $post = new stdClass;
        $content = $frontMatter->parse(file_get_contents($v));
       
        // Split the date & slug from file name
        $arr = explode('_', $v);
        $post->date = strtotime(str_replace('posts/','',$arr[0]));
        $post->slug = basename($arr[1], '.md');
        
        // Get the contents and convert it to HTML
		$meta = $content->getData();
		$post->title = $meta['title'];
		$post->image = $meta['image'];
		$post->excerpt = $meta['excerpt'];
		$post->tags = array_map('trim', explode(',', $meta['tags'])); // Split tags on comma, trim whitespace
        $post->body = convert_markdown($content->getContent());

        $tmp[] = $post;
    }

    return $tmp;
}

function get_single($slug) {
	$frontMatter = new Webuni\FrontMatter\FrontMatter();
	$single = get_post_list($slug);
	
    $post = new stdClass;
    $content = $frontMatter->parse(file_get_contents($single[0]));
    
    $arr = explode('_', $single[0]);
    $post->date = strtotime(str_replace('posts/','',$arr[0]));
    
    // Get the contents and convert it to HTML
	$meta = $content->getData();
	$post->title = $meta['title'];
	$post->image = $meta['image'];
	$post->excerpt = $meta['excerpt'];
	$post->tags = array_map('trim', explode(',', $meta['tags'])); // Split tags on comma, trim whitespace
    $post->body = convert_markdown($content->getContent());
    
    return $post;
}

// Convert markdown to HTML
function convert_markdown($post) {
	$markdown = new ParsedownExtra();	
	$post = $markdown->text($post);
	return $post;
}

// Convert array of posts into JSON
function generate_json($posts) {
    return json_encode($posts, JSON_PRETTY_PRINT);
}

// Load plugins from /plugins/ folder 
function load_plugins() {
	$plugins = array_filter(glob('plugins/*'), 'is_dir');
	foreach($plugins as $plugin) {
		$plugin = ltrim($plugin, 'plugins/');
		require 'plugins/' . $plugin . '/' . $plugin . '.php';
	}
}

function load_theme($themeName) {
	require 'themes/' . $themeName . '/functions.php';
	
	global $router;
	
	$router->map('GET','/', function() { 
		$posts = get_posts();
		require 'themes/' . FRONTEND_THEME . '/index.php';
	}, 'home');
	
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