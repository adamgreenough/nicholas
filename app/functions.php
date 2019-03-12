<?php

// Return list of all posts, newest first
function get_post_list($slug = '*', $date = '*') {
    static $_cache = array();

    if(empty($_cache)){
        $_cache = array_reverse(glob('posts/' . $date . '_' . $slug . '.md'));
    }

    return $_cache;
}

function get_tag_list($tag) {
	$frontMatter = new Webuni\FrontMatter\FrontMatter();
	$files = array_reverse(glob('posts/*.md'));
    
	foreach($files as $k => $v) {
	    $post = new stdClass;
	    $content = $frontMatter->parse(file_get_contents($v));
	    
	    $meta = $content->getData();
		$post->tags = array_map('trim', explode(',', $meta['tags'])); // Split tags on comma, trim whitespace
		$post->tags = array_map('strtolower', $post->tags);
	
		if(in_array(strtolower($tag), $post->tags)) {
	    	$tmp[] .= $v;
	    }
	}
	
	return $tmp;	
}

// Return an array of posts
function get_posts($page = 1, $perPage = POSTS_PER_PAGE, $tag = null) {
    $frontMatter = new Webuni\FrontMatter\FrontMatter();
    
    if ($tag == null) {
    	$posts = get_post_list();
    } else {
	    $posts = get_tag_list($tag);
	}
	
	// Check we found some posts
	if(!$posts) {
	    return false;
	}

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
	
	if($single[0]) { // Check post exists
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
}

// Convert markdown to HTML
function convert_markdown($content) {
	$markdown = new ParsedownExtra();	
	$content = $markdown->text($content);
	return $content;
}

// Convert array of posts into JSON
function generate_json($posts) {
    return json_encode($posts, JSON_PRETTY_PRINT);
}

// Convert array of posts into RSS
function generate_rss($posts) {
    $feed = new Suin\RSSWriter\Feed();
    $channel = new Suin\RSSWriter\Channel();

    $channel
        ->title(BLOG_NAME)
        ->description(BLOG_DESCRIPTION)
        ->appendTo($feed);

    foreach($posts as $p){
        $item = new Suin\RSSWriter\Item();
        $item
            ->title($p->title)
            ->description($p->body)
            ->appendTo($channel);
    }

    return $feed;
}

// Load plugins from /plugins/ folder 
function load_plugins() {
	$plugins = array_filter(glob('plugins/*'), 'is_dir');
	foreach($plugins as $plugin) {
		$plugin = ltrim($plugin, 'plugins');
		require 'plugins/' . $plugin . '/' . $plugin . '.php';
	}
}