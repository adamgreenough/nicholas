<?php get_header(); ?>

<h1>Posts tagged "<?= $tag ?>"</h1>
		
<?php foreach ($posts as $post) { ?>		

<article class="blog-preview">
	<a href="/<?= BASE_URL . $post->slug; ?>/">
		<h2><?= $post->title; ?></h2>
	</a>
	<p><?= $post->excerpt; ?></p>
	<p class="small"><?= date('jS F Y', $post->date); ?> • Filed under <?= display_tag_list($post->tags); ?></p>
</article>

<?php } ?>

<?php get_pagination_link($page, $posts, $tag)['next']; ?>
<?php get_pagination_link($page, $posts, $tag)['prev']; ?>		

<?php get_footer(); ?>