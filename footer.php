		<footer>
			<h6><?php _e('be the best'); ?></h6>
			<p><?php $d = date('Y'); _e("Copyright $d by Oz Ramos") ?></p>
		</footer>

		<?php //=======================================================================
		// JavaScript Variables
		//========================================================================*/ ?>
		<script>
			bloginfo = {
				ajax_url: 	"<?php echo admin_url('admin-ajax.php'); ?>"
			};

			i18n = {
				form_validation_no_text: 				"<?php _e('Please fill out %label_text%', 'Page: Contact'); ?>",
				form_validation_bad_email: 				"<?php _e('Please fill out a valid email', 'Page: Contact'); ?>"
			}
		</script>


		<?php //=======================================================================
		// NoScript fixes
		//========================================================================*/ ?>
		<noscript>
			<style>
				[reveal]{
					opacity: 1;
				}
			</style>
		</noscript>

		<?php wp_footer(); ?>
	</body>
</html>