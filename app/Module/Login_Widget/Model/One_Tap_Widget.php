<?php

namespace oneTap\Module\Login_Widget\Model;

use \WP_Error;
use oneTap\Base\Helper;

class One_Tap_Widget {

     /**
     * Constuctor
     *
     */
    function __construct() {
		add_action( 'wp_footer', array( $this, 'onetap_one_tap_widget' ), 50 );
		add_action( 'login_footer', array( $this, 'onetap_one_tap_widget' ), 50 );
        add_action( 'init', array( $this, 'shortcodes' ) );

        if ( Helper\get_option( 'nativeLogin', true )) {
			add_action( 'login_form', array( $this, 'wp_login_form' ) ) ;
			add_action( 'woocommerce_login_form_start', array( $this, 'wp_login_form' ));
		}

        if ( Helper\get_option( 'directoristLogin', true )) {
			add_action( 'atbdp_before_login_form_end', array( $this, 'wp_login_form' ) ) ;
			add_action( 'atbdp_before_user_registration_submit', array( $this, 'wp_login_form' ) ) ;
		}
    }

    public function wp_login_form(){
		?>
		<div id="wp-login-google-login-button">
			<div class="g_id_signin"
				data-type="standard"
				data-shape="rectangular"
				data-theme="outline"
				data-text="continue_with"
				data-size="large"
				data-logo_alignment="center"
				data-width="270">
			</div>
			<div style="text-align: center; margin: 10px 0;">Or</div>
		</div>
		<?php
	}

    public function shortcodes() {
		add_shortcode( 'onetap_google_login_button', array( $this, 'onetap_login_btn' ) );
	}

	public function onetap_login_btn(){
		if ( !is_user_logged_in() && Helper\guard() ) {
			$html = '<div class="g_id_signin"
			data-type="standard"
			data-shape="rectangular"
			data-theme="outline"
			data-text="continue_with"
			data-size="large"
			data-logo_alignment="center">
			</div>';
			return $html;
		}
		return '';
	}


    public function onetap_one_tap_widget() {
		$nonce = wp_create_nonce( 'onetap-login-widget' );
		if ( !is_user_logged_in() && Helper\guard() ) {
			$clintID = Helper\get_option('clintID');
			$attributes = Helper\widget_attributes();
			?>
			<div id="g_id_onload"
				data-client_id="<?php echo esc_attr( $clintID );?>"
				data-wpnonce="<?php echo esc_attr( $nonce );?>"
				<?php 
				foreach( $attributes as $attr => $value ) { ?>
				data-<?php echo esc_attr( $attr ); ?>="<?php echo esc_attr( $value); ?>"
				<?php } ?>		
			>
			</div>
			<?php
		}
	}

 
}

