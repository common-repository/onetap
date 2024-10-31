<?php

namespace oneTap\Module\Login_Widget\Model;

use Directorist\Asset_Loader\Helper as Asset_LoaderHelper;
use Directorist\Helper as DirectoristHelper;
use \WP_Error;
use \Google_Client;
use \WP_User;
use oneTap\Base\Helper;

use function oneTap\Base\Helper\onetap_clean;
use function WPWaxCustomerSupportApp\Base\Helper\get_option;

class Controller {

     /**
     * Constuctor
     *
     */
    function __construct() {
		add_action( 'init', array( $this, 'onetap_widget_endpoint' ) );
    }

	public function onetap_widget_endpoint(){

		if ( array_key_exists('onetap-signin', $_GET) ) {

			$nonce = ! empty( $_POST['wpnonce'] ) ? onetap_clean( wp_unslash( $_POST['wpnonce'] ) ) : '';
			if ( !wp_verify_nonce( $nonce, 'onetap-login-widget') ) {
				error_log( 'onetap - wpnonce failed' );
				return;
			}

			if ( !isset($_POST['g_csrf_token']) && !empty($_POST['g_csrf_token']) ) {
				error_log( 'onetap - post g_csrf_token not available' );
				return;
			}

			if ( !isset($_COOKIE['g_csrf_token']) && !empty($_COOKIE['g_csrf_token']) ) {
				error_log( 'onetap - cookie g_csrf_token not available' );
				return;
			}

			if ( $_POST['g_csrf_token'] != $_COOKIE['g_csrf_token'] ) {
				error_log( 'onetap - g_csrf_token is not same in post and cookie' );
				return;
			}

			if ( !isset($_POST['credential']) && !empty($_POST['credential']) ) {
				error_log( 'onetap - credential is not available' );
				return;
			}

			$id_token = sanitize_text_field( wp_unslash( $_POST['credential'] ) );

			$clintID = Helper\get_option('clintID');
			$redirectUrl = Helper\get_option('redirectUrl', home_url());
			$signupRedirectUrl = Helper\get_option('signupRedirectUrl', home_url());

			$client = new Google_Client(['client_id' => esc_html($clintID)]);
			$payload = $client->verifyIdToken($id_token);

			if ($payload) {

				$wp_user = get_user_by('email', sanitize_email($payload['email']));
				$redirect_uri = ! empty( $_POST['redirect_uri'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_uri'] ) ) : '';

				if ($wp_user) {
					$action = $this->login_user($wp_user->ID, $payload, $redirectUrl );
				}else{
					$action = $this->register_user($payload, $signupRedirectUrl );
				}

				if( is_wp_error( $action ) ) {
					error_log( 'onetap - '.print_r($action) );
					return;
				}

			} else {
				error_log( 'onetap - invaild id' );
				return;
			}

		}
	}

	public function register_user($payload, $redirect_uri){
		$errors = new WP_Error();

		$username_parts = array();
		if ( isset( $payload['given_name'] ) ) {
			$username_parts[] = sanitize_user( $payload['given_name'], true );
		}

		if ( isset( $payload['family_name'] ) ) {
			$username_parts[] = sanitize_user( $payload['family_name'], true );
		}

		if ( empty( $username_parts ) ) {
			$email_parts    = explode( '@', $payload['email'] );
			$email_username = $email_parts[0];
			$username_parts[] = sanitize_user( $email_username, true );
		}

		$username = strtolower( implode( '.', $username_parts ) );

		$default_user_name = $username;
		$suffix=1;
		while (username_exists($username)) {
			$username = $default_user_name . $suffix;
			$suffix++;
		}
		$new_userid = register_new_user( sanitize_user($username), $payload['email'] );
		
		if (is_wp_error($new_userid)) {
			$errors->add( 'registration_failed', __( '<strong>Error</strong>: Registration Failed' ) );
		}

		$user_data = array();
		$user_data['ID'] = $new_userid;
		$user_data['first_name'] = $payload['given_name'];
		$user_data['last_name'] = $payload['family_name'];
		$user_data['display_name'] = $payload['name'];

		$user_id = wp_update_user($user_data);
		// $default_role = Helper\get_option( 'defaultRole' );
		// if( ! is_wp_error( $user_id ) && $default_role ) {
		// 	$user = new WP_User( $user_id );
		// 	// Remove role
		// 	$user->remove_role( 'subscriber' );
		// 	// Add role
		// 	$user->add_role( $default_role );
		// }

		update_user_meta($new_userid, 'onetap_profilepicture_url', esc_url_raw($payload['picture']));
		update_user_meta($new_userid, 'nickname', $payload['given_name']);

		wp_set_current_user ( $new_userid );
		wp_set_auth_cookie  ( $new_userid, true );

		if ( $errors->has_errors() ) {
			return $errors;
		}

		if ( wp_safe_redirect( $redirect_uri ) ) {
			exit;
		}
	}

	public function login_user($id, $payload, $redirect_uri){
		
		$update = Helper\get_option('updateExistingUser', false);

		if( $update ) {
			$user_data = array();
			$user_data['ID'] = $id;
			$user_data['first_name'] = $payload['given_name'];
			$user_data['last_name'] = $payload['family_name'];
			$user_data['display_name'] = $payload['name'];
			wp_update_user($user_data);
			update_user_meta($id, 'nickname', $payload['given_name']);
		}

		update_user_meta($id, 'onetap_profilepicture_url', esc_url_raw($payload['picture']));
		wp_clear_auth_cookie();
		wp_set_current_user ( $id );
		wp_set_auth_cookie ( $id, true );

		if ( wp_safe_redirect( $redirect_uri ) ) {
			exit;
		}
	}
 
}

