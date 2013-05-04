<?php /* Template Name: Search */ ?>
<?php get_header(); ?>
	<div class="main" id="the-page">
		<?php //=======================================================================
		// Search Workbooks
		//========================================================================*/ ?>
		<section>
			<div class="title-block">
				<h1><?php _e('Search'); ?></h1>
				<h2><?php _e('our catalog of workbooks'); ?></h2>
			</div>

			<form method="post">
				<div class="group">
					<label for="search-keywords"><?php _e('Keywords'); ?></label>
					<input type="text" id="search-keywords" name="keywords">
				</div>
				<div class="group chosen">
					<label for="search-areas" onClick="$('#search_areas_chzn .search-field input').focus();"><?php _e('Areas'); ?></label>
					<select id="search-areas" name="area" placeholder="<?php _e('Select Areas'); ?>" multiple rel="chosen" no-results-text="<?php _e('No Areas found.'); ?>">
						<option>Algebra</option>
						<option>00-XX General</option>
						<option>00-XX History and Biography</option>
					</select>
				</div>

				<input type="submit" name="submit" value="search">
			</form>
		</section>
	</div>
<?php get_footer(); ?>