<?php //###########################################################################
// Our custom comment form
//###########################################################################
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Exit if you're a snooper
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) {
		header("Location: http://jadobe.com/");
		die();
	}

	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Exit if you need a password
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!empty($post->post_password)){
		if($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password){
			echo '<p><b>' . __('Sorry, but this comment is password protected.') . '</b></p>';
		}
	}

	//=============================================================================
	// Comment Form
	//=============================================================================
	if(comments_open()){
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Requires registration, but not logged in
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		if(get_option('comment_registration') && !$user_ID){
			echo '<div><i>Sorry, you need to <a href="'.wp_login_url(get_permalink()).'">log in</a> to comment.</i></div>';
		} else {
			//=============================================================================
			// Echo the form and the security fields
			//=============================================================================
			echo '<form action="' . admin_url('admin-ajax.php') . '" method="post" id="commentform" rel="ajax" callback="callback_commented">';
			?>
				<?php wp_nonce_field('comment', 'nonce_comment'); ?>
				<input type="text" class="hidden" name="action" value="comment">
				<input type="text" class="hidden" name="timestamp" value="<?php echo time(); ?>">
				<input type="text" class="hidden" name="spam">
				<?php comment_id_fields($post->ID); do_action('comment_form', $post->ID); ?>
			<?php 

				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Show the standard comment form
				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				if($user_ID): global $current_user; ?>
					<div class="group-generic">
						<aside><?php _e('Logged in as'); ?></aside>
						<div class="comment-header">
							<a href="<?php bloginfo('url') ?>/wp-admin/profile.php"><?php echo $user_identity; ?> <?php echo get_avatar($current_user->ID, 96) ?></a>							
						</div>
					</div>
					<br>
				<?php //- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// Not logged in, so ask for email and website
				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				else : ?>
					<div class="group">
						<label for="name"><?php _e('Name'); ?></label>
						<input type="text" id="name" name="name" validate="text">
					</div>
					<div class="group">
						<label for="email"><?php _e('Mail <small>(not published)</small>'); ?></label>
						<input type="text" id="email" name="email" validate="email">
					</div>					
				<?php endif;?>

				<div class="group">
					<label for="comment"><?php _e('Comment'); ?></label>
					<textarea name="comment" id="comment" validate="text"></textarea>
				</div>
				<input type="submit" id="submit" value="<?php _e('Submit Comment'); ?>">
				<?php cancel_comment_reply_link(__('Cancel Repy')); ?> &nbsp;
			<?php echo '</form>';
		}
	} else {
		echo '<p><i>Sorry, comments have been closed. Please send me a <a href="http://twitter.com/thejadobe" target="_blank">Tweet</a> if you\'d like to reopen the discussion!</i></p>';
	}

	//=============================================================================
	// Display Comments
	//=============================================================================
	if($comments){
		wp_list_comments(array(
			'callback'		=> 'custom_comment_callback',
			'end-callback'	=> '__return_false',
			'style'			=> 'div',
			'avatar_size'	=> 64
		));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// No Comments
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	} else {
		echo '<p><i>'.__('No one has commented yet. Be the first!').'</i></p>';
	}


	//###########################################################################
	// Custom Callback for the comments
	//###########################################################################
	function custom_comment_callback($comment, $args, $depth){
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Comment is awaiting moderation
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		global $post;

		if($comment->comment_approved == '0'){
			echo '<p><b>' . __('Your comment is awaiting approval.') . '</b></p>';
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		// Display Comment
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		} else {
			//=============================================================================
			// Get Comment User Meta
			//=============================================================================
			if($user = get_user_by('email', $comment->comment_author_email)){
				$userUrl = '<a href="' . str_replace('mathiom.com', $user->data->user_nicename . '.mathiom.com', get_bloginfo('url')) . '">' . $user->data->display_name . '</a>';
			} else {
				$userUrl = $comment->comment_author;
			}
			//=============================================================================
			// Create the comment
			//=============================================================================
		?>
			<div class="group-comment depth-<?php echo min($depth-1, 3) ?> <?php if($post->post_author === $comment->user_id) echo 'author' ?>" id="comment-<?php echo $comment->comment_ID ?>">
				<aside>
					<p>
						<?php echo get_avatar($comment->comment_author_email, $args['avatar_size']) ?>
						<span class="author"><?php echo $userUrl ?></span>
						<span class="date"><?php echo date('d, F Y', strtotime($comment->comment_date)) ?></span>
					</p>
				</aside>
				<div>
					<p>
						<?php echo htmlspecialchars($comment->comment_content); ?>
					</p>
					<p class="align-right"><?php comment_reply_link(array('respond_id' => 'commentform', 'add_below' => 'comment', 'depth' => 1, 'max_depth' => 3), $comment->comment_ID) ?></p>
				</div>
			</div>
		<?php }
	}
?>