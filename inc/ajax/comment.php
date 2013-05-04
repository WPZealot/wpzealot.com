<?php //=======================================================================
// Comments Form
//========================================================================== 
add_action('wp_ajax_comment', 'ajax_comment');
add_action('wp_ajax_nopriv_comment', 'ajax_comment');
function ajax_comment(){
	extract($_POST);

	//=============================================================================
	// Security & Verification
	//=============================================================================
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Required Variables (different depending if the user is logged in)
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(is_user_logged_in()){
		if(!isset($nonce_comment) || !isset($action) || !isset($timestamp) || !isset($comment) || !isset($spam) || !isset($comment_post_ID)) send_json(__('Oops, looks like the page didn\'t load properly! Please refresh the page and try again.', 'AJAX'));
	} else {
		if(!isset($nonce_comment) || !isset($action) || !isset($timestamp) || !isset($name) || !isset($email) || !isset($comment) || !isset($spam) || !isset($comment_post_ID)) send_json(__('Oops, looks like the page didn\'t load properly! Please refresh the page and try again.', 'AJAX'));
	}
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Too Soon
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if($timestamp + 10 > time()) send_json(__('Please try again in (' . strval($timestamp + 10 -time()) . ') seconds.' , 'Page: Contact'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Validate Nonce
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!wp_verify_nonce($nonce_comment, 'comment')) send_json(__('Oops, looks like the server could not verify your credentials. Please refresh the page and try again.'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Honey Pot
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(strlen(strval($spam))) send_json(__('Please refresh the page and try again.','Page: Contact'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Invalid Email
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!is_user_logged_in() && !filter_var($email, FILTER_VALIDATE_EMAIL)) send_json(__('Please provide a valid email.', 'Page: Contact'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Invalid Post ID
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	$comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
	$post = get_post($comment_post_ID);
	if ( empty($post->comment_status) ) send_json(__('Oops, looks like the server could not verify your credentials. Please refresh the page and try again.'));

	//=============================================================================
	// Determine if we even post, and if so prepare the comment
	//=============================================================================
	$status = get_post_status($post);
	$status_obj = get_post_status_object($status);
	if(!comments_open($comment_post_ID)){
		send_json(__('Sorry, comments have been closed!'));
	} elseif('trash' == $status){
		send_json(__('Sorry, this post has been removed!'));
	} elseif(!$status_obj->public && !$status_obj->private){
		send_json(__('Sorry, this post is down for maintenance!'));
	} elseif ( post_password_required($comment_post_ID) ) {
		send_json(__('Sorry, this post has since been password protected!'));
	} else {
		do_action('pre_comment_on_post', $comment_post_ID);
	}

	//=============================================================================
	// Get comment meta
	//=============================================================================
	$comment_author       = (isset($name)) ? $name : null;
	$comment_author_email = (isset($email)) ? $email : null;
	$comment_author_url   = null;	//Not using URLs for now
	$comment_content      = $comment;

	//=============================================================================
	// Get the user
	//=============================================================================
	$user = wp_get_current_user();
	if ( $user->exists() ) {
		if ( empty( $user->display_name ) )
			$user->display_name=$user->user_login;
		global $wpdb;
		$comment_author       = $wpdb->escape($user->display_name);
		$comment_author_email = $wpdb->escape($user->user_email);
		$comment_author_url   = $wpdb->escape($user->user_url);
		if ( current_user_can('unfiltered_html') ) {
			if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
				kses_remove_filters(); // start with a clean slate
				kses_init_filters(); // set up the filters
			}
		}
	} else {
		if ( get_option('comment_registration') || 'private' == $status )
			send_json( __('Sorry, you must be logged in to post a comment on this post.') );
	}

	//=============================================================================
	// Final post meta and add the comment
	//=============================================================================
	$comment_type = '';
	$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
	$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
	$comment_id = wp_new_comment( $commentdata );
	$comment = get_comment($comment_id);

	//=============================================================================
	// Comment return status and message
	//=============================================================================
	$json['comment_status'] = $comment->comment_approved;
	switch($comment->comment_approved){
		case 1:
			$json['message'] = __('Your comment was added successfully!');
		break;
		default:
			$json['message'] = __('Your comment was added to the moderation queue. Thanks!');
	}
	$json['comment_message'] = htmlspecialchars($comment->comment_content);

	//=============================================================================
	// Post Comment
	//=============================================================================
	send_json($json, 1);
}