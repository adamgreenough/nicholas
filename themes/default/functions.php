<?php
	
function display_tag_list($tags) {
	$config = include('config.php');
	$output = '';
	
	foreach ($tags as $tag) {
		$output .= 	'<a href="' . $config['base_url'] . '/tag/' . strtolower($tag) . '/" title="Posts tagged ' . $tag . '">' . $tag . '</a>, ';
	}
	
	$output = rtrim($output, ', ');
	return $output;
}