<?php global $wp_query; ?>
<?php get_header(); ?>
	<div class="main" id="the-page">
		<section>
			<div class="title-block">
				<h1><?php _e('404'); ?></h1>
				<h2><?php _e('page not found'); ?></h2>
			</div>

			<h2><?php _e('Search'); ?></h2>
			<form action="<?php bloginfo('url') ?>" role="search" method="get" id="searchform">
				<div class="group">
					<label for="_404-title"><?php _e('Keywords', '404'); ?></label>
					<input type="text" id="_404-title" name="s" value="<?php echo esc_attr($wp_query->query_vars['name']); ?>">
				</div>
				<input type="submit" value="search">
			</form>
		</section>
	</div>
<?php get_footer(); ?>