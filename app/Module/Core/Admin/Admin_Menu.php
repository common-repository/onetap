<?php

namespace oneTap\Module\Core\Admin;

use oneTap\Base\Helper;

class Admin_Menu {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    public function admin_menu() {
        add_menu_page( __( 'oneTap', 'onetap' ), __( 'oneTap', 'onetap' ), 'manage_options', 'onetap', [$this, 'onetap_config'], 'dashicons-google', 77 );

        // add_submenu_page( 'onetap', __( 'Go Pro', 'onetap' ), __( 'Go Pro', 'onetap' ), 'manage_options', 'goolist-pro', [$this, 'pro'] );
    }

    public function onetap_config() {
        Helper\get_the_view( 'admin-ui/settings' );
    }

    public function pro() {
        Helper\get_the_view( 'admin-ui/integrations' );
    }

}
