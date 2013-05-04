<?php //=======================================================================
// Handle the Contact Form
//========================================================================*/ 
add_action('wp_ajax_contact_send_message', 'ajax_contact_send_message');
add_action('wp_ajax_nopriv_contact_send_message', 'ajax_contact_send_message');
function ajax_contact_send_message(){
	extract($_POST);
	//=============================================================================
	// Security & Verification
	//=============================================================================
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Required Variables
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!isset($nonce_contact) || !isset($action) || !isset($timestamp) || !isset($name) || !isset($email) || !isset($message) || !isset($spam))
		send_json(__('Oops, looks like the page didn\'t load properly! Please refresh the page and try again.', 'AJAX'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Too Soon
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if($timestamp + 10 > time()) send_json(__('Please try again in (' . strval($timestamp + 10 -time()) . ') seconds.' , 'Page: Contact'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Validate Nonce
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!wp_verify_nonce($nonce_contact, 'contact_message')) send_json(__('Oops, looks like the server could not verify your credentials. Please refresh the page and try again.'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Honey Pot
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(strlen(strval($spam))) send_json(__('Please refresh the page and try again.','Page: Contact'));
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	// Invalid Email
	//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) send_json(__('Please provide a valid email.', 'Page: Contact'));

	//=============================================================================
	// Send the email
	//=============================================================================
	$message = "DATE: ".date('l jS \of F Y h:i:s A')."\r\nSENDER: $name\r\nEMAIL: $email\r\n\r\n".$message;
	$mailed = mail('me@omgcarlos.com', '[Mathiom] ' . $name . ' is trying to contact you.', $message, 'From: ' . $email);

	if(!$mailed) send_json(__('Message not sent. Please check your name and email and try again.'));
	else send_json(__('Message sent!'), 1);

	send_json(__('Oops, something went wrong! Please try again or refresh the page if the problem persists.', 'AJAX'));
}