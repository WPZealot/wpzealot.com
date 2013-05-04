<?php get_header(); ?>
	<div class="main" id="the-page">

		<?php //=======================================================================
		// Title Block
		//========================================================================*/ ?>
		<div class="title-block">
			<h1><?php the_title(); ?></h1>
			<h2><?php echo get_post_meta($post->ID, '_subtitle', true); ?></h2>
			<?php if($intro = get_post_meta($post->ID, '_intro', true)) echo '<p>' . $intro . '</p>'; ?>
		</div>

		<?php //=======================================================================
		// Content
		//========================================================================*/ ?>
		<?php if(have_posts()): while(have_posts()): the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; else: ?>
			<?php _e('This page is under construction.'); ?>				
		<?php endif; ?>
		
		<?php //=======================================================================
		// Comments
		//========================================================================*/ ?>
		<h2>Discussion</h2>
		<?php if($commentText = get_post_meta($post->ID, '_comment-text', true)) echo '<p>' . $commentText . '</p>'; ?>
		<hr>
		<?php comments_template(); ?>
	</div>
<?php get_footer(); ?>