<!doctype HTML>
<html>
<head>
	<title><?= $title; ?></title>
	<meta charset="UTF-8">
	<meta name="description" content="<?= $description; ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="<?= get_theme_directory_url(); ?>/assets/normalize.min.css" type="text/css">	
	<link rel="stylesheet" href="<?= get_theme_directory_url(); ?>/assets/styles.css" type="text/css">
</head>
<body>
	<header class="site-header">
		<a href="<?= BASE_URL ?>/">
			<div class="logo"><?= substr($config['blog_name'], 0, 1) ?></div> <?= $config['blog_name'] ?>
		</a>
	</header>
	
	<div class="container">