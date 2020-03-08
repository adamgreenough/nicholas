<?php get_header($page->title); ?>

<article>
	<h1><?= $page->title; ?></h1>	

	<?= $page->body; ?>
</article>

<?php get_footer(); ?>