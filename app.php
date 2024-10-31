<?php

use oneTap\Module;
use oneTap\Helper;

final class oneTap {

    /**
     * @var mixed
     */
    private static $instance;

    /**
     * Constructor
     *
     * @return void
     */
    private function __construct() {

        // Load Textdomain
        add_action( 'plugins_loaded', [$this, 'load_textdomain'] );

        // Register Controllers
        $controllers = $this->get_controllers();
        Helper\Serve::register_services( $controllers );

    }

    /**
     * Get Instance
     *
     * @return oneTap
     */
    public static function get_instance() {

        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Get Controllers
     *
     * @return array Controllers
     */
    protected function get_controllers() {
        return [
            // Module
            Module\Init::class,
        ];
    }

    /**
     * Load Text Domain
     *
     * @return void
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'onetap', false, ONETAP_LANGUAGES );
    }

    /**
     * Cloning instances of the class is forbidden.
     *
     * @return void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'onetap' ), '1.0' );
    }

    /**
     * Unserializing instances of the class is forbidden.
     *
     * @return void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'onetap' ), '1.0' );
    }

}
