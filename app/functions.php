<?php

// Return list of all posts, newest first
function get_post_list() {
    static $_cache = array();

    if(empty($_cache)){
        $_cache = array_reverse(glob('posts/*.md'));
    }

    return $_cache;
}

// Return an array of posts
function get_posts($page = 1, $perpage = 10) {
    $posts = get_post_list();

    // Extract a specific page with results
    $posts = array_slice($posts, ($page-1) * $perpage, $perpage);

    $tmp = array();

    foreach($posts as $k=>$v) {
        $post = new stdClass;

        // Extract the date
        $arr = explode('_', $v);
        $post->date = strtotime(str_replace('posts/','',$arr[0]));

        // Get the contents and convert it to HTML
        $content = file_get_contents($v);

        // Extract the title and body
        $arr = explode('</h1>', $content);
        $post->title = str_replace('<h1>','',$arr[0]);
        $post->body = $arr[1];

        $tmp[] = $post;
    }

    return $tmp;
}

// Convert array of posts into JSON
function generate_json($posts) {
    return json_encode($posts, JSON_PRETTY_PRINT);
}