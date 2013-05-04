<?php //=======================================================================
// (cont. template-member.php)
// Login form (if member is logged out)
//========================================================================*/ ?>
<div class="main" id="the-page">

	<?php //=======================================================================
	// Title Block
	//========================================================================*/ ?>
	<div class="title-block">
		<h1><?php _e('Login / Register'); ?></h1>
		<h2><?php _e('register to take full advantage of mathiom') ?></h2>
		<p></p>
	</div>

	<?php //=======================================================================
	// Login/Registration Form
	//========================================================================*/ ?>
	<form method="post" rel="ajax" action="<?php echo admin_url('admin-ajax.php'); ?>">
		<?php wp_nonce_field('login', 'nonce_login'); ?>
		<input type="text" class="hidden" name="action" value="login">
		<input type="text" id="login-timestamp" name="timestamp" value="<?php echo time(); ?>" style="position: fixed; z-index: 0; top: 200%;">
		<input type="text" id="login-spam" name="spam" style="position: fixed; z-index: 0; top: 200%;">

		<div class="group">
			<label for="login-email"><?php _e('Email'); ?></label>
			<input name="email" id="login-email" validate="email">
		</div>

		<div class="group">
			<label for="login-password"><?php _e('Password'); ?></label>
			<input type="password" name="password" id="login-password" validate="text">
		</div>

		<div class="group">
			<label for="login-remember"><?php _e('Stay logged in?'); ?></label>
			<input type="checkbox" name="remember" id="login-remember">
			<label class="login-remember"></label>
		</div>

		<div class="group">
			<label for="login-comfirm-password"><?php _e('Confirm Password *'); ?></label>
			<input type="password" name="comfirm_password" id="login-comfirm-password">
			<p class="align-right"><small>* only required if registering</small></p>
		</div>

		<div class="message-ajax"></div>

		<input type="submit" name="submit_login" value="Login">
		<input type="submit" name="submit_register" value="Register">
	</form>
</div>