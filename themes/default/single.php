<?php get_header(); ?>

<div class="container">
	<div class="row site-content">
		<div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
			<h1><?= $post->title; ?></h1>	
			<p class="lead">Posted on <?= date('jS F Y', $post->date); ?> • Filed under <?= display_tag_list($post->tags); ?>
		
			<?= $post->body; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>