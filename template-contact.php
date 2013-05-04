<?php /* Template Name: Contact Page */ ?>
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

		<form method="post" rel="ajax" action="<?php echo admin_url('admin-ajax.php'); ?>">
			<?php wp_nonce_field('contact_message', 'nonce_contact'); ?>
			<input type="text" class="hidden" name="action" value="contact_send_message">
			<input type="text" class="hidden" id="contact-timestamp" name="timestamp" value="<?php echo time(); ?>">
			<input type="text" class="hidden" id="contact-spam" name="spam">

			<div class="group">
				<label for="contact-name"><?php _e('Name'); ?></label>
				<input type="text" id="contact-name" name="name" validate="text">
			</div>
			<div class="group">
				<label for="contact-email"><?php _e('Email'); ?></label>
				<input type="text" id="contact-email" name="email" validate="text">
			</div>
			<div class="group">
				<label for="contact-message"><?php _e('Message'); ?></label>
				<textarea name="message" id="contact-message" validate="text"></textarea>
			</div>

			<div class="message-ajax"></div>

			<input type="submit" name="submit" value="Send Message">
		</form>
	</div>
<?php get_footer(); ?>