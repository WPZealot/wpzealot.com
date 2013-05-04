<?php //=======================================================================
// Login a user
// :: For registering, see /inc/ajax/register.php
// :: For logout, see /inc/ajax/logout.php
//========================================================================*/

add_action('wp_ajax_login', 'ajax_login');
add_action('wp_ajax_nopriv_login', 'ajax_login');
function ajax_login(){
	extract($_POST);
	//=============================================================================
	// Security & Verification
	//=============================================================================
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Required Variables
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!isset($nonce_login) || !isset($action) || !isset($timestamp) || !isset($email) || !isset($password) || !isset($spam))
		send_json(__('Oops, looks like the page didn\'t load properly! Please refresh the page and try again.', 'AJAX'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Too Soon
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if($timestamp + 1 > time()) send_json(__('Please try again in (' . strval($timestamp + 10 -time()) . ') seconds.' , 'Page: Contact'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Validate Nonce
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!wp_verify_nonce($nonce_login, 'login')) send_json(__('Oops, looks like the server could not verify your credentials. Please refresh the page and try again.'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Honey Pot
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(strlen(strval($spam))) send_json(__('Please refresh the page and try again.','Page: Contact'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Invalid Email
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) send_json(__('Please provide a valid email.', 'Page: Contact'));

	//=============================================================================
	// Login
	//=============================================================================
	$user = email_exists($email);
	if(!$user) send_json(__('Sorry, this is a bad user/password combination.'));
	$user = get_userdata($user);
	if(!$user) send_json(__('Sorry, this is a bad user/password combination.'));
	$user = wp_authenticate($user->user_login, $password);
	if(!is_wp_error($user)){
		wp_signon(array(
			'user_login'	=> $user->user_login,
			'user_password' => $password,
			'remember'		=> isset($remember)
		));
	} else {
		send_json(__('Sorry, this is a bad user/password combination.'));
	}

	send_json('oh hi');
}