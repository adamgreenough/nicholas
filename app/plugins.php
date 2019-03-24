<?php
	
// Load plugins (/x/x.php) from /plugins/ folder 
function load_plugins() {
	$plugins = array_filter(glob('plugins/*'), 'is_dir');
	foreach($plugins as $plugin) {
		$plugin = ltrim($plugin, 'plugins');
		require 'plugins/' . $plugin . '/' . $plugin . '.php';
	}
}