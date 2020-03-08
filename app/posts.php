<?php

// Return list of all posts
function get_post_list($slug = '*', $date = '*') {
    static $postList = array();

    if(empty($postList)){
        $postList = array_reverse(glob('posts/' . $date . '_' . $slug . '.md'));
    }

    return $postList;
}

// Return list of all posts within a tag
function get_tag_list($tag) {
	$frontMatter = new Webuni\FrontMatter\FrontMatter();

    static $postList = array();

    if(empty($postList)){
        $postList = array_reverse(glob('posts/*.md'));
    }
    
	foreach($postList as $post => $value) {
	    $post = new stdClass;
	    $content = $frontMatter->parse(file_get_contents($value));
	    
	    $meta = $content->getData();
		$post->tags = array_map('trim', explode(',', $meta['tags'])); // Split tags on comma, trim whitespace
		$post->tags = array_map('strtolower', $post->tags);
	
		if(in_array(strtolower(urldecode($tag)), $post->tags)) {
			$tagList[] .= $value;
		}
	}
	
	return $tagList;	
}

// Return an array of posts with front matter parsed
function get_posts($page = 1, $perPage = null, $tag = null) {
    $frontMatter = new Webuni\FrontMatter\FrontMatter();
    $config = include('config.php'); 
    
    if ($perPage == null) {
	    $perPage = $config['posts_per_page'];
	}
    
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
		if($arr[1]) {	
			$post->slug = basename($arr[1], '.md');
		}
		
		// Get the contents and convert it to HTML
		$meta = $content->getData();
		$post->title = $meta['title'] ?? 'No title';
		$post->body = convert_markdown($content->getContent());
		$post->image = $meta['image'] ?? '';
		$post->excerpt = $meta['excerpt'] ?? substr($post->body, 0, 140);
		$post->tags = array_map('trim', explode(',', $meta['tags'] ?? '')) ?? ''; // Split tags on comma, trim whitespace
		
		$tmp[] = $post;
    }

    return $tmp;
}

// Get the full contents of a single post
function get_single($slug, $year = '*', $month = '*') {
	$frontMatter = new Webuni\FrontMatter\FrontMatter();
	$date = $year . '-' . $month . '-*';
	
	$single = get_post_list($slug, $date);
	
	if($single[0]) { // Check post exists
	    $post = new stdClass;
	    $content = $frontMatter->parse(file_get_contents($single[0]));
	    
	    $arr = explode('_', $single[0]);
	    $post->date = strtotime(str_replace('posts/','',$arr[0]));
	    
		// Get the contents and convert it to HTML
		$meta = $content->getData();
		$post->title = $meta['title'] ?? 'No title';
		$post->image = $meta['image'] ?? '';
		$post->excerpt = $meta['excerpt'] ?? substr($post->body, 0, 140);
		$post->tags = array_map('trim', explode(',', $meta['tags'])) ?? ''; // Split tags on comma, trim whitespace
		$post->body = convert_markdown($content->getContent());
	    
	    return $post;
    }
}