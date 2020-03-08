<?php

// Return list of all pages
function get_page_list($slug = '*') {
	static $pageList = array();

	if(empty($pageList)){
		$pageList = array_reverse(glob('pages/' . $slug . '.md'));
	}

	return $pageList;
}

// Get the full contents of a single page
function get_page($page) {
	$frontMatter = new Webuni\FrontMatter\FrontMatter();
	$content = get_page_list($page);
	
	if($content) { // Check page exists
		$page = new stdClass;
		$content = $frontMatter->parse(file_get_contents($content[0]));
		
		// Get the contents and convert it to HTML
		$meta = $content->getData();
		$page->title = $meta['title'];
		$page->body = convert_markdown($content->getContent());
		
		return $page;
	}
}