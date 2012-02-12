<?php
/**
 * This file contents functionalities related to user account, such as signup, signin, profile and settings.
 *
 * @since 0.0.1
 */

/**
 * Filter the build-in login url to the custom one.
 * /wp-login.php to /signin
 * 
 * @since 0.0.1
 *
 * @todo Need fix the reauth missing error
 */
function vp_filter_login_url( $login_url, $redirect ) {
  $login_url = vp_get_page_url_by_slug( 'signin' );
  
  if ( !empty( $redirect ) )
    $login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
  
  //if ( $force_reauth )
    //$login_url = add_query_arg( 'reauth', '1', $login_url );
  
  return $login_url;
}
add_filter( 'login_url', 'vp_filter_login_url', 10, 2 );

/**
 * Generate the user infomation list displayed in member page.
 *
 * @since 0.0.1
 */
function vp_member_info_list() {
  $user_id = get_query_var( 'author' );
  $user = get_user_by( 'id', $user_id );
  $site = $user->user_url;
  $followers = sprintf( _n( '%d follower', '%d followers', vp_get_followers_count(), 'v2press' ), vp_get_followers_count() );;
  
  $output = '<ul class="member-info-list">';
  
  if ( !empty( $site ) )
    $output .= '<li class="member-site"><a href="' . $site. '" rel="external">' . $site. '</a></li>';
  
  $output .= '<li class="member-followers">' . $followers. '</li>';
  $output .= '</ul>';
  
  echo $output;
}


/* =============================================================================
 * Signup form
 *
 * @since 0.0.1
 ============================================================================ */
 
/**
 * User signup form used at Signup page.
 *
 * @since 0.0.1
 *
 * @use vp_signup_form_fields()
 * @for [vp-signup-form] shortcode
 */
function vp_signup_form() {
 
	// only show the registration form to non-logged-in members
	if( !is_user_logged_in() ) {
 
		$registration_enabled = get_option( 'users_can_register' );
 
		if( $registration_enabled ) {
			$output = vp_signup_form_fields();
		} else {
			$output = __( 'User registration is not enabled.', 'v2press' );
		}
		
		return $output;
	}
}
add_shortcode( 'vp-signup-form', 'vp_signup_form' );

/**
 * The reCAPTCHA form field.
 *
 * @since 0.0.1
 */
function vp_recaptcha_field() {
  require_once( VP_LIBS_PATH . '/recaptchalib.php' );
  $publickey = "6LeIKM0SAAAAALmomciSzl1AQhI1pd_LUdWdr6T9";
  
  $lang = WPLANG;
  if ( defined( $lang ) && '' == $lang )
    $lang = 'en_US';
  $lang_parts = split( '_', WPLANG );
  $lang = join( '-', $lang_parts );
  
  $custom = <<<reTHEME
<script type="text/javascript">
  var RecaptchaOptions = {
    theme : 'white',
    lang : '$lang'
 };
</script>
reTHEME;
  echo $custom . recaptcha_get_html($publickey);
}

/**
 * The signup form fields.
 *
 * @since 0.0.1
 *
 * @for vp_signup_form()
 */
function vp_signup_form_fields() { 
	ob_start();

	vp_error_messages();
?>
<form id="signup-form" action="" method="post">
	<fieldset>
		<p>
			<label for="vp_user_login"><?php _e( 'Username', 'v2press' ); ?></label>
			<input type="text" name="vp_user_login" id="vp_user_login" class="form-field required" value="<?php echo $_POST['vp_user_login'] ?>" />
			<span class="field-helper"><?php _e( 'Use alphanumeric, _, - only. Within 4-12 characters.', 'v2press' ); ?></span>
		</p>
		<p>
			<label for="vp_user_email"><?php _e( 'Email', 'v2press' ); ?></label>
			<input type="email" name="vp_user_email" id="vp_user_email" class="form-field required" value="<?php echo $_POST['vp_user_email'] ?>" />
			<span class="field-helper"><?php _e( 'Please use your real and most used email.', 'v2press' ); ?></span>
		</p>
		<p>
			<label for="vp_user_password"><?php _e( 'Password', 'v2press' ); ?></label>
			<input type="password" autocomplete="off" name="vp_user_password" id="vp_user_password" class="form-field required" />
		</p>
		<p>
			<label for="vp_user_password_confirmation"><?php _e( 'Password Again', 'v2press' ); ?></label>
			<input type="password" autocomplete="off" name="vp_user_password_confirmation" id="vp_user_password_confirmation" class="form-field required" />
		</p>
		<?php vp_recaptcha_field(); ?>
		<p>
			<input type="hidden" name="vp_signup_nonce" value="<?php echo wp_create_nonce( 'vp-signup-nonce' ); ?>" />
			<input type="hidden" name="action" value="vp_signup" />
			<input type="submit" class="btn push-right" value="<?php _e( 'Create Account', 'v2press' ); ?>" />
		</p>
	</fieldset>
</form>
<?php
  vp_form_nav();
  
	return ob_get_clean();
}

/**
 * Do signup a user.
 *
 * @since 0.0.1
 */
function vp_do_signup() {
  if ( !isset($_POST['action']) || 'vp_signup' != $_POST['action'] )
    return;

  if ( isset( $_POST['vp_user_login'] )
       && wp_verify_nonce( $_POST['vp_signup_nonce'], 'vp-signup-nonce')) {
		$user_login		= $_POST['vp_user_login'];	
		$user_email		= $_POST['vp_user_email'];
		$user_pwd     = $_POST['vp_user_password'];
		$pwd_confirm 	= $_POST['vp_user_password_confirmation'];
 
    // Username already taken
		if( username_exists( $user_login ) ) {
			vp_errors()->add( 'username_unavailable', __( 'Username already taken', 'v2press' ) );
		}
		
		// Username invalid
		if( !validate_username( $user_login ) ) {	
			vp_errors()->add( 'username_invalid', __( 'Invalid username', 'v2press' ) );
		}
		
		// Empty username
		if( empty( $user_login ) ) {
			vp_errors()->add( 'username_empty', __( 'Please enter a username', 'v2press' ) );
		}
		
		// Username out range (4-12)
		if ( !empty( $user_login ) && ( 4 > strlen( $user_login ) || 12 < strlen( $user_login ) ) ) {
		  vp_errors()->add( 'username_out_range', __( 'Username length must within 4-12 characters', 'v2press' ) );
		}
		
		// Empty email
		if ( empty( $user_email ) ) {
		  vp_errors()->add( 'email_empty', __( 'Please enter your Email', 'v2press' ) );
		}
		
		// Invalid email
		if( !empty( $user_email ) && !is_email( $user_email ) ) {
			vp_errors()->add( 'email_invalid', __( 'Invalid email', 'v2press' ) );
		}
		
		// Email address already registered
		if( !empty( $user_email ) && email_exists( $user_email ) ) {	
			vp_errors()->add( 'email_used', __( 'This Email already registered', 'v2press' ) );
		}
		
		// Empty password
		if( empty( $user_pwd ) ) {
			vp_errors()->add( 'password_empty', __( 'Please enter a password', 'v2press' ) );
		}
		
		// Password not match
		if( $user_pwd != $pwd_confirm ) {
			vp_errors()->add( 'password_mismatch', __( 'Passwords do not match', 'v2press' ) );
		}
		
		// reCAPTCHA error
		require_once( VP_LIBS_PATH . '/recaptchalib.php' );
		$privatekey = "6LeIKM0SAAAAAHe216bBiA8qWyDOgvIcCifUysM7";
		$resp = recaptcha_check_answer( $privatekey,
		                                $_SERVER["REMOTE_ADDR"],
		                                $_POST["recaptcha_challenge_field"],
		                                $_POST["recaptcha_response_field"] );
		
		if ( !$resp->is_valid ) {
		  vp_errors()->add( 'recaptcha-error', __( 'reCAPATCH is not right.', 'v2press' ) );
		}
 
		$errors = vp_errors()->get_error_messages();
		// only create the user if there are no errors
		if( empty( $errors ) ) {
			$new_user_id = wp_insert_user(array(
					'user_login'		=> $user_login,
					'user_pass'	 		=> $user_pwd,
					'user_email'		=> $user_email,
					'user_registered'	=> date('Y-m-d H:i:s'),
					'role'				=> 'author'
				)
			);
			
			if( $new_user_id ) {
				// send an email to the admin alerting them of the registration
				wp_new_user_notification( $new_user_id );
 
				// log the new user in
				wp_setcookie( $user_login, $user_pwd, true );
				wp_set_current_user( $new_user_id, $user_login );	
				do_action( 'wp_login', $user_login );
 
				// send the newly created user to the home page after logging them in
				wp_redirect( home_url() );
				exit;
			} 
		} // END if empty $errors 
	} // END if isset( $_POST['vp_user_login']
} // END vp_do_signup()
add_action( 'template_redirect', 'vp_do_signup' );


/* =============================================================================
 * Signin form
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * User signin form used at Signin page.
 *
 * @since 0.0.1
 */
function vp_signin_form() {
 
	// Only display login form when not logged in
	if( !is_user_logged_in() ) {
		$output = vp_signin_form_fields();
	}
	
	return $output;
}
add_shortcode( 'vp-signin-form', 'vp_signin_form' );

/**
 * User signin form fields.
 *
 * @since 0.0.1
 */
function vp_signin_form_fields() { 
	ob_start();
	
	vp_error_messages();
	
	$redirect_to = $_REQUEST['redirect_to'];
	if ( !empty( $redirect_to ) ) :
?>
<div class="form-message alert">
  <p><?php _e( 'You need signin to view this page.', 'v2press' ); ?></p>
</div>
<?php 
  endif;
  
  $from = $_REQUEST['from'];
  if ( !empty( $from ) && ( 'rp' == $from ) ) :
?>
<div class="form-message info">
  <p><?php _e( 'Please signin with your new password.', 'v2press' ); ?></p>
</div>
<?php endif; ?>
<form id="signin-form" action="" method="post">
	<fieldset>
		<p>
			<label for="vp_user_login"><?php _e( 'Username', 'v2press' ); ?></label>
			<input type="text" name="vp_user_login" id="vp_user_login" class="form-field required" />
		</p>
		<p>
			<label for="vp_user_password"><?php _e( 'Password', 'v2press' ); ?></label>
			<input type="password" autocomplete="off" name="vp_user_password" id="vp_user_password" class="form-field required" />
		</p>
		<p>
		  <input class="push-right" type="checkbox" name="vp_rememberme" checked="checked" value="forever" />
		  <label for="vp_rememberme" class="normal"><?php _e( 'Remeber me?', 'v2press' ); ?></label>
		</p>
		<p>
<?php if ( !empty( $redirect_to ) ) { ?>
      <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
<?php } ?>
			<input type="hidden" name="vp_signin_nonce" value="<?php echo wp_create_nonce( 'vp-signin-nonce' ); ?>" />
			<input type="hidden" name="action" value="vp_signin" />
			<input class="btn push-right" type="submit" value="<?php _e( 'Signin', 'v2press' ); ?>" />
		</p>
	</fieldset>
</form>
<?php
  vp_form_nav();
  
	return ob_get_clean();
}

/**
 * Do signin a user.
 *
 * @since 0.0.1
 */
function vp_do_signin() {
  if ( !isset($_POST['action']) || 'vp_signin' != $_POST['action'] )
    return;
  
	if( isset( $_POST['vp_user_login'] )
	    && wp_verify_nonce( $_POST['vp_signin_nonce'], 'vp-signin-nonce') ) {
 
		$user = get_userdatabylogin( sanitize_user( $_POST['vp_user_login'] ) );
		
		// User not exists
		if( !$user ) {
			vp_errors()->add( 'username_invalid', __( 'Invalid username', 'v2press' ) );
		}
 
		// No password was entered
		if( !empty( $_POST['vp_user_login'] ) && empty( $_POST['vp_user_password'] ) ) {
			vp_errors()->add( 'password_empty', __( 'Please enter a password', 'v2press' ) );
		}
 
		// Password is incorrect for the specified user
		if( !empty( $_POST['vp_user_password'] ) && !wp_check_password( $_POST['vp_user_password'], $user->user_pass, $user->ID ) ) {
			vp_errors()->add( 'password_incorrect', __( 'Incorrect password', 'v2press' ) );
		}
 
		$errors = vp_errors()->get_error_messages();
 
		if( empty( $errors ) ) {
		  $rememberme = (bool) !empty( $_POST['vp_remeberme']);
			wp_set_auth_cookie( $user->ID, true );
			wp_set_current_user( $user->ID, $_POST['vp_user_login'] );	
			do_action( 'wp_login', $_POST['vp_user_login'] );
 
      $redirect_to = $_POST['redirect_to'];
			if ( !empty( $redirect_to ) ) {
			  wp_safe_redirect( $redirect_to );
			} else {
			  wp_redirect( home_url() );
			}
			exit;
		} // END if empty( $errors )
	} // END if isset( $_POST['vp_user_login'] )
} // END vp_do_signin()
add_action( 'template_redirect', 'vp_do_signin' );


/* =============================================================================
 * Forgot password form
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Forgot password form shortcode.
 *
 * @since 0.0.1
 */
function vp_forgot_password_form() {
  if( !is_user_logged_in() ) {
    return vp_forgot_password_form_fields();
  }
}
add_shortcode( 'vp-forgot-password-form', 'vp_forgot_password_form' );

/**
 * Forgot password form fields.
 *
 * @since 0.0.1
 */
function vp_forgot_password_form_fields() {
  ob_start();
  
  vp_error_messages();
  
  if ( isset( $_POST['vp_user_login'] ) && $_GET['sent'] && !empty( $_GET['sent'] ) ) :
?>
<div class="form-message success">
  <?php _e( 'The reset password instructions has already sent to you email, please check you inbox and follow the instructions.', 'v2press' ); ?>
</div>
<?php else: ?>
<div class="form-message info">
  <p><?php _e( 'Please enter the username and email you used when signup to reset your password.', 'v2press' ); ?></p>
</div>
<?php endif; ?>
<form id="forgot-password-form" action="" method="post">
  <fieldset>
    <p>
      <label for="vp_user_login"><?php _e( 'Username', 'v2press' ); ?></label>
      <input type="text" name="vp_user_login" id="vp_user_login" class="form-field required" />
    </p>
    <p>
      <label for="vp_user_email"><?php _e( 'Email', 'v2press' ); ?></label>
      <input type="email" name="vp_user_email" id="vp_user_email" class="form-field required" />
    </p>
    <p>
      <input type="hidden" name="vp_forgot_password_nonce" value="<?php echo wp_create_nonce( 'vp-forgot-password-nonce' ); ?>" />
      <input type="hidden" name="action" value="vp_forgot_password" />
      <input type="submit" class="btn push-right" value="<?php _e( 'Sent Reset Instructions', 'v2press' ); ?>" />
    </p>
  </fieldset>
</form>
<?php
  return ob_get_clean();
}

/**
 * Do the forgot password form process.
 *
 * @since 0.0.1
 *
 * @todo Maybe. Strict the user must reset password within 24 hours after instructions been sent.
 */
function vp_do_forgot() {
  if ( !isset( $_POST['action'] ) || 'vp_forgot_password' != $_POST['action'] )
    return;
  
  if( isset( $_POST['vp_user_login'] )
      && wp_verify_nonce( $_POST['vp_forgot_password_nonce'], 'vp-forgot-password-nonce') ) {
    
    $user_login = $_POST['vp_user_login'];
    $email = $_POST['vp_user_email'];
    
    // Username empty
    if ( empty( $user_login ) ) {
      vp_errors()->add( 'username_empty', __( 'Please enter your username.', 'v2press' ) );
    }
    
    // Email empty
    if ( empty( $email ) ) {
      vp_errors()->add( 'email_empty', __( 'Please enter your email.', 'v2press' ) );
    }
    
    if( !empty( $user_login) ) {
      $user = get_userdatabylogin( sanitize_user( $_POST['vp_user_login'] ) );
      // Username incorrect
      if ( !$user ) {
        vp_errors()->add( 'username_invalid', __( 'Invalid username', 'v2press' ) );
      }
    }
    
    // Username and email not match
    global $wpdb;
    if ( !empty( $user_login ) && !empty( $email ) ) {
      $email_in_db = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM $wpdb->users WHERE user_login = %s", $user_login ) );
      if ( $email !== $email_in_db ) {
        vp_errors()->add( 'email_invalid', __( 'This is not the email you used when signup.', 'v2press' ) );
      }
    }
    
    // Reset instructions already sent
    $active_key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login ) );
    if ( !empty( $active_key ) ) {
      vp_errors()->add( 'already_sent', __( 'Your reset password instructions already sent, please check you inbox, or may be spam area.', 'v2press') );
    }
    
    $errors = vp_errors()->get_error_messages();
    if ( empty( $errors ) ) {
      $key = wp_generate_password( 20, false );
      
      // Now insert the new md5 key into the db
      $wpdb->update( $wpdb->users, array( 'user_activation_key' => $key ), array( 'user_login' => $user_login ) );
      
      // The email content
      $message = __( 'Someone requested that the password be reset for the following account:', 'v2press' ) . "\r\n\r\n";
      $message .= home_url() . "\r\n\r\n";
      $message .= sprintf(__( 'Username: %s', 'v2press' ), $user_login) . "\r\n\r\n";
      $message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', 'v2press' ) . "\r\n\r\n";
      $message .= __( 'To reset your password, visit the following address:', 'v2press' ) . "\r\n\r\n";
      $message .= '<' . vp_get_page_url_by_slug( 'reset', "key=$key&login=" . rawurlencode($user_login) ) . ">\r\n";
      
      // The email subject
      $subject = sprintf( __('[%s] Password Reset', 'v2press' ), wp_specialchars_decode( get_option('blogname'), ENT_QUOTES ) );
      
      if ( $message && !wp_mail( $email, $subject, $message ) )
      	wp_die( __( 'The e-mail could not be sent.', 'v2press' ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function...', 'v2press' ) );
      
      add_query_var( 'sent', 'true', vp_current_url() );
    } // END if empty errors
  } // END if isset( $_POST['vp_user_login']
} // END vp_do_forgot()
add_action( 'template_redirect', 'vp_do_forgot' );


/* =============================================================================
 * The reset password form.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * The reset password shortcode function.
 *
 * @since 0.0.1
 */
function vp_reset_password_form() {
  if ( !is_user_logged_in() && isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {
    $key = trim( $_GET['key'] );
    $user_login = trim( $_GET['login'] );
      
    global $wpdb;
    // Username and activation key not match
    $user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_login = %s AND user_activation_key = %s", $user_login, $key ) );
    
    if ( empty( $user ) ) {
      return '<p>' . __( 'Please do not cheating me.', 'v2press' ) . '</p>';
    } else {
      return vp_reset_password_form_fields();
    }
    
  } else {
    return '<p>' . __( "You don't have permission to view this page.", 'v2press' ) . '</p>';
  }
}
add_shortcode( 'vp-reset-password-form', 'vp_reset_password_form' );

/**
 * The reset password form fields.
 *
 * @since 0.0.1
 */
function vp_reset_password_form_fields() {
  ob_start();
  
  vp_error_messages();
  
?>
<div class="form-message info">
  <p><?php _e( 'Please enter your new password and confirm it to reset your password.', 'v2press' ); ?></p>
</div>
<form id="reset-password-form" action="" method="post">
  <fieldset>
    <p>
      <label for="vp_new_password"><?php _e( 'New Password', 'v2press' ); ?></label>
      <input type="password" autocomplete="off" name="vp_new_password" id="vp_new_password" class="form-field required" value="" />
    </p>
    <p>
      <label for="vp_new_password_confirmation"><?php _e( 'Password Again', 'v2press' ); ?></label>
      <input type="password" autocomplete="off" name="vp_new_password_confirmation" id="vp_new_password_confirmation" class="form-field required" value="" />
    </p>
    <p>
      <input type="hidden" name="vp_reset_password_nonce" value="<?php echo wp_create_nonce( 'vp-reset-password-nonce' ); ?>" />
      <input type="hidden" name="action" value="vp_reset_password" />
      <input type="submit" class="btn push-right" value="<?php _e( 'Reset Password', 'v2press' ); ?>" />
    </p>
  </fieldset>
</form>
<?php
  return ob_get_clean();
}

/**
 * Do the reset password form process.
 *
 * @since 0.0.1
 */
function vp_do_reset() {
  if ( !isset( $_POST['action']) || 'vp_reset_password' != $_POST['action'] )
    return;
  
  if ( isset( $_POST['vp_new_password'] )
       && wp_verify_nonce( $_POST['vp_reset_password_nonce'], 'vp-reset-password-nonce') ) {
    
    $new_pwd = $_POST['vp_new_password'];
    $new_pwd_confirm = $_POST['vp_new_password_confirmation'];
    
    // New password empty
    if ( empty( $new_pwd ) ) {
      vp_errors()->add( 'new_password_empty', __( 'Please enter your new password.', 'v2press' ) );
    }
    
    // Password confirmation empty
    if ( empty( $new_pwd_confirm ) ) {
      vp_errors()->add( 'new_password_confirmation-empty', __( 'Please retype your new password.', 'v2press' ) );
    }
    
    // Password confirmation incorrect
    if ( !empty( $new_pwd ) && !empty( $new_pwd_confirm ) && ( $new_pwd !== $new_pwd_confirm ) ) {
      vp_errors()->add( 'password_mismatch', __( 'Passwords do not match', 'v2press' ) );
    }
    
    $errors = vp_errors()->get_error_messages();
    if( empty( $errors ) ) {
      $user_login = $_GET['login'];
      $user = get_userdatabylogin( sanitize_user( $user_login ) );
      
      if ( !empty( $user ) ) {
        wp_set_password( $new_pwd, $user->ID );      
        wp_redirect( vp_get_page_url_by_slug( 'signin', 'from=rp') );
        exit;
      }
    } // END if empty( $errors )  
  } // END if isset( $_POST['vp_new_password'] )
} // END vp_do_reset()
add_action( 'template_redirect', 'vp_do_reset' );


/* =============================================================================
 * User settings form on Settings page.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * User settings form on Settings page.
 *
 * This function is called directly in Settings page, but not use shortcode.
 *
 * @since 0.0.1
 */
function vp_settings_form() {
  if ( is_user_logged_in() ) {
    echo vp_settings_form_fields();
  }
}

/**
 * User settings form fields.
 *
 * @since 0.0.1
 */
function vp_settings_form_fields() {
  $user = wp_get_current_user();
  
  ob_start();
  
  if ( isset( $_POST['action'] ) && ( 'vp_update_setting' == $_POST['action'] ) )
    vp_error_messages();
  
  if( isset( $_GET['updated'] ) && 'true' == $_GET['updated'] ) :
?>
<div class="form-message success">
  <p><?php _e( 'Your settings is updated', 'v2press' ); ?></p>
</div>
<?php
  endif;
?>
<form id="settings-form" action="" method="post">
  <fieldset>
    <p>
      <label for="vp_user_login"><?php _e( 'Username', 'v2press' ); ?></label>
      <input type="text" name="vp_user_login" id="vp_user_login" class="form-field required disabled" disabled="disabled" value="<?php echo $user->user_login; ?>" />
      <span class="field-helper"><?php _e( 'Username cannot be changed.', 'v2press' ); ?></span>
    </p>
    <p>
      <label for="vp_user_email"><?php _e( 'Email', 'v2press' ); ?></label>
      <input type="email" name="vp_user_email" id="vp_user_email" class="form-field required" value="<?php echo $user->user_email; ?>" />
    </p>
    <p>
      <label for="vp_user_url"><?php _e( 'Website', 'v2press' ); ?></label>
      <input type="url" name="vp_user_url" id="vp_user_url" class="form-field" value="<?php echo esc_url($user->user_url); ?>" />
    </p>
    <p>
      <label for="vp_user_bio"><?php _e( 'Biographical Info', 'v2press' ); ?></label>
      <textarea rows="5" cols="30" name="vp_user_bio" id="vp_user_bio" class="form-field"><?php echo $user->description; ?></textarea>
    </p>
    <p>
      <label for="vp_user_lang"><?php _e( 'Languange', 'v2press' ); ?></label>
      <?php vp_lang_dropdown(); ?>
    </p>
    <p>
      <input type="hidden" name="vp_settings_form_nonce" value="<?php echo wp_create_nonce( 'vp-settings-form-nonce' ); ?>" />
      <input type="hidden" name="action" value="vp_update_settings" />
      <input type="submit" class="btn push-right" value="<?php _e( 'Update Settings', 'v2press' ); ?>" />
    </p>
  </fieldset>
</form>
<?php
  return ob_get_clean();
}

/**
 * Do the settings form process.
 *
 * @since 0.0.1
 */
function vp_do_update_settings() {
  if ( isset( $_POST['vp_user_email' ])
       && wp_verify_nonce( $_POST['vp_settings_form_nonce'], 'vp-settings-form-nonce' ) ) {
    
    $user_id = wp_get_current_user()->ID;
    $user_email = $_POST['vp_user_email'];
    $user_url = $_POST['vp_user_url'];
    $user_bio = $_POST['vp_user_bio'];
    $user_lang = $_POST['vp_user_lang'];
    
    // Not check if username is empty, 'cause its input field is disabled
      
    // Email empty
    if ( empty( $user_email ) ) {
      vp_errors()->add( 'email_empty', __( 'Please enter your email.', 'v2press' ) );
    }
    
    // Invalid email
    if( !empty( $user_email ) && !is_email( $user_email ) ) {
    	vp_errors()->add( 'email_invalid', __('Invalid email', 'v2press' ) );
    }
    
    $email_used_by_other = false;
    $other_user_id = get_user_by_email( $user_email )->ID;
    if ( !empty($other_user_id) && wp_get_current_user()->ID !== $other_user_id ) {
      $email_used_by_other = true;
    }
    // Email address already used by another user
    if( !empty( $user_email ) && $email_used_by_other ) {	
    	vp_errors()->add( 'email_used_by_other', __('This Email already used by another user', 'v2press' ) );
    }
    
    $errors = vp_errors()->get_error_messages();
    if ( empty( $errors ) ) {
      $user_data = array(
        'ID' => $user_id,
        'user_email' => $user_email,
        'user_url' => $user_url,
        'description' => $user_bio
      );      
      wp_update_user( $user_data );
      update_user_meta( $user_id, 'v2press_my_language', $user_lang );
      
      wp_redirect(add_query_arg( 'updated', 'true', vp_current_url() ));
      exit;
    } // END if empty( $errors )
  } // END if isset( $_POST['vp_user_login' ])
} // END vp_do_update_settings()
add_action( 'init', 'vp_do_update_settings' );


/* =============================================================================
 * Change password form on Settings page.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Change password form function on Settings page.
 *
 * @since 0.0.1
 */
function vp_change_password_form() {
  if ( is_user_logged_in() ) {
    echo vp_change_password_form_fields();
  }
}

/**
 * Change password form fields.
 *
 * @since 0.0.1
 */
function vp_change_password_form_fields() {
  ob_start();
  
  if ( isset( $_POST['action'] ) && ( 'vp_change_password' == $_POST['action'] ) )
    vp_error_messages();
  
  if ( isset($_GET['cp'] ) && 'true' == $_GET['cp'] ) :
?>
<div class="form-message success">
  <p><?php _e( 'Your password is changed, and your are now signed in with your new password.', 'v2press' ); ?></p>
</div>
<?php endif; ?>
<p class="fade xsmall bold"><?php _e( 'If you don\'t want to change password, please don\'t enter either of the three fields following.', 'v2press' ); ?></p>
<form id="change-password-form" method="post" action="#change-password-box">
  <fieldset>
    <p>
      <label for="vp_current_password"><?php _e( 'Current Password', 'v2press' ); ?></label>
      <input type="password" autocomplete="off" name="vp_current_password" id="vp_current_password" class="form-field required" value="" />
    </p>
    <p>
      <label for="vp_new_password"><?php _e( 'New Password', 'v2press' ); ?></label>
      <input type="password" autocomplete="off" name="vp_new_password" id="vp_new_password" class="form-field required" value="" />
    </p>
    <p>
      <label for="vp_new_password_confirmation"><?php _e( 'Password Again', 'v2press' ); ?></label>
      <input type="password" autocomplete="off" name="vp_new_password_confirmation" id="vp_new_password_confirmation" class="form-field required" value="" />
    </p>
    <p>
      <input type="hidden" name="vp_change_password_nonce" value="<?php echo wp_create_nonce( 'vp-change-password-nonce' ); ?>" />
      <input type="hidden" name="action" value="vp_change_password" />
      <input type="submit" class="btn push-right" value="<?php _e( 'Change Password', 'v2press' ); ?>" />
    </p>
  </fieldset>
</form>
<?php
  return ob_get_clean();
}

/**
 * Do the change password form process.
 *
 * @since 0.0.1
 */
function vp_do_change_password() {
  if( isset( $_POST['vp_current_password'] )
      && wp_verify_nonce( $_POST['vp_change_password_nonce'], 'vp-change-password-nonce' ) ) {
    $current_pwd = $_POST['vp_current_password'];
    $new_pwd = $_POST['vp_new_password'];
    $new_pwd_confirm = $_POST['vp_new_password_confirmation'];
    
    // Current password empty
    if ( empty( $current_pwd ) ) {
      vp_errors()->add( 'current_password_empty', __( 'Please enter your current password', 'v2press' ) );
    }
    
    // New password empty
    if ( empty( $new_pwd ) ) {
      vp_errors()->add( 'new_password_empty', __( 'Please enter your new password.', 'v2press' ) );
    }
    
    // Password confirmation empty
    if ( empty( $new_pwd_confirm ) ) {
      vp_errors()->add( 'new_password_confirmation_empty', __( 'Please retype your new password.', 'v2press' ) );
    }
    
    // Not use your current password again
    if ( !empty( $current_pwd ) && !empty( $new_pwd ) && ( $new_pwd == $current_pwd ) ) {
      vp_errors()->add( 'password_used', __( 'Please user a password different from current one.', 'v2press' ) );
    }
    
    // Password confirmation incorrect
    if ( !empty( $new_pwd ) && !empty( $new_pwd_confirm ) && ( $new_pwd !== $new_pwd_confirm ) ) {
      vp_errors()->add( 'password_mismatch', __( 'Passwords do not match', 'v2press' ) );
    }
    
    $errors = vp_errors()->get_error_messages();
    if( empty( $errors ) ) {
      $user_data = array(
        'ID' => wp_get_current_user()->ID,
        'user_pass' => $new_pwd
      );
      
      wp_update_user( $user_data );
      
      wp_redirect( add_query_arg( 'cp', 'true', vp_current_url() . '#change-password-box' ) );
      exit;
    }
  } // END if isset( $_POST['vp_current_password'] )
} // END vp_do_change_password()
add_action( 'init', 'vp_do_change_password' );
 

/* =============================================================================
 * Handle form errors
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Tracking the form errors.
 *
 * @since 0.0.1
 */
function vp_errors(){
  static $wp_error; // Will hold global variable safely
  return isset($wp_error) ? $wp_error : ( $wp_error = new WP_Error( null, null, null ) );
}

/**
 * Display error messages after form submitted, if any.
 *
 * @since 0.0.1
 */
function vp_error_messages() {
  // Only show when there are any errors
	if( $codes = vp_errors()->get_error_codes() ) {
		echo '<div class="form-message error"><ul class="errors-list">';
		
		// Loop error codes and display errors
		foreach( $codes as $code ) {
		  $message = vp_errors()->get_error_message( $code );
		  echo '<li class="error-item">' . $message . '</li>';
		}
		echo '</ul></div>';
	}
}

/**
 * Form Navigation links.
 *
 * @since
 */
function vp_form_nav() {
  $output = '<div class="form-nav">';
  
  if ( is_page( 'signin' ) && get_option( 'users_can_register' ) ) {
    $output .= vp_page_link( array( 'slug' => 'signup', 'display' => false ) ) . ' | ';
  } elseif ( is_page( 'signup' ) ) {
    $output .= vp_page_link( array( 'slug' => 'signin', 'display' => false ) ) . ' | ';
  }
  
  $output .= vp_page_link( array( 'slug' => 'forgot', 'text' => __( 'Forgot password?', 'v2press' ), 'display' => false ) );
  $output .= '</div>';
  
  echo $output;
}


/* =============================================================================
 * Rewrite a author's posts archive page url, also known as profile page in V2Press.
 * From /t/author/andor to /member/andor, which andor is user's nice name.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Change the default author page's rewrite base from 'author' to 'member'.
 *
 * @since 0.0.1
 */
function vp_author_rewrite() {
  global $wp_rewrite;
  
  // Not need the $wp_rewrite->front
  $wp_rewrite->author_structure = 'member/%author%';
  
  $wp_rewrite->flush_rules();
}
add_action( 'init', 'vp_author_rewrite' );


/* =============================================================================
 * Handle user specified language setting.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Hook the 'locale' filter.
 *
 * @since 0.0.1
 */
function vp_l10n( $locale ) {
  global $locale;
  
  if ( defined( 'WPLANG' ) )
    $locale = WPLANG;
    
  $lang_option = get_user_meta( get_current_user_ID(), 'v2press_my_language', true );
  if ( !empty( $lang_option ) ) {
    $locale = $lang_option;
  }
  
  if ( empty( $locale ) )
    $locale = 'en_US';
  
  return $locale;
}
add_filter( 'locale', 'vp_l10n' );

/**
 * Generate the language setting dropdown list.
 *
 * @since 0.0.1
 */
function vp_lang_dropdown() {
  $default_code = WPLANG;
  if ( empty( $default_code ) )
    $default_code = 'en_US'; 

  $option = get_user_meta( get_current_user_ID(), 'v2press_my_language', true );
  $langs = array( 'en_US' => 'English', 'zh_CN' => '简体中文' );
  
  $output = '<select name="vp_user_lang" id="vp_user_lang">';
  
  foreach ( $langs as $code => $name ) {
    $output .= '<option value="' .$code . '"';
    if ( !empty( $option ) && $code == $option )
      $output .= ' selected="selected"';
      
    if ( empty( $option ) && $code == $default_code )
      $output .= ' selected="selected"';
    
    $output .= '>' . $name . '</option>';
  }
  $output .= '</select>';
  
  echo $output;
}