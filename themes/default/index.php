<?php get_header(); ?>

<div class="container">
	<div class="row site-content">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<h1>Blog</h1>
		
				<?php foreach ($posts as $post) { ?>		

				<div class="blog-preview">
					<a href="/blog/<?= $post->slug; ?>/">
						<h2><?= $post->title; ?></h2>
						<p><?= $post->excerpt; ?></p>
					</a>
					<p class="small"><?= date('jS F Y', $post->date); ?> • Filed under <?= display_tag_list($post->tags); ?></p>
				</div>
				
				<?php } ?>
		
		</div>
	</div>
</div>

<?php get_footer(); ?>