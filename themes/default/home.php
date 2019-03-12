<?php get_header(); ?>

		
<?php foreach ($posts as $post) { ?>		

<article class="blog-preview">
	<a href="<?= $post->slug; ?>/">
		<h2><?= $post->title; ?></h2>
	</a>
	<p><?= $post->excerpt; ?></p>
	<p class="small"><?= date('jS F Y', $post->date); ?> • Filed under <?= display_tag_list($post->tags); ?></p>
</article>

<?php } ?>
		
<?php get_next_page_link($page, $posts); ?>
<?php get_prev_page_link($page, $posts); ?>

<?php get_footer(); ?>