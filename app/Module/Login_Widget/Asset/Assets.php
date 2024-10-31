<?php

namespace oneTap\Module\Login_Widget\Asset;
class Assets {

    /**
     * Constuctor
     *
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 50 );
        add_action( 'login_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1 );
        add_action( 'login_footer', array( $this, 'wp_login_script' ), 50 );

    }

    public function wp_login_script(){
		?>
		<script type="text/javascript">
			jQuery("#wp-login-google-login-button").prependTo("#loginform");
		</script>
		<?php
	}

   /**
	 * Enqueuing Scripts
	 */
	public function enqueue_scripts() {
		wp_register_script( 'one-tap-client-js', 'https://accounts.google.com/gsi/client', array(), false, false );
		wp_enqueue_script( 'one-tap-client-js' );
	}

}