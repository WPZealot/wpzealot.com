<?php /* Template Name: Member Dashboard */ ?>
<?php get_header(); ?>
	<?php if(is_user_logged_in()) include get_template_directory() . '/template-member-logged-in.php'; else include get_template_directory() . '/template-member-logged-out.php'; ?>
<?php get_footer(); ?>