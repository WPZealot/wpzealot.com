<?php get_header(); 
	global $wp_query;
	$totalResults = $wp_query->found_posts;
?>
	<div class="main" id="the-page">

		<?php //=======================================================================
		// Title Block
		//========================================================================*/ ?>
		<div class="title-block">
			<h1><?php printf(__('%1$s results for %2$s', 'search'), $totalResults, esc_html(_GET('s'))); ?></h1>
			<h2>
				<form action="<?php bloginfo('url') ?>" role="search" method="get" id="searchform">
					<div class="group">
						<label for="_search-title"><?php _e('Search Again', 'search'); ?></label>
						<input type="text" id="_search-title" name="s" value="">
					</div>
					<input type="submit" value="search">
				</form>				
			</h2>
			<?php if($intro = get_post_meta($post->ID, '_intro', true)) echo '<p>' . $intro . '</p>'; ?>
		</div>

		<?php //=======================================================================
		// Results
		//========================================================================== ?>
		<?php if(have_posts()): while(have_posts()): the_post(); ?>
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php echo substr($post->post_content, 0, 280); ?> 
			<br><a href="<?php the_permalink() ?>">...read article...</a>
		<?php endwhile; endif; ?>
	</div>
<?php get_footer(); ?>