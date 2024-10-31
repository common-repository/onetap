<?php

namespace oneTap\Module\Settings_Panel\Asset;

use Directorist\Asset_Loader\Helper as Asset_LoaderHelper;
use Elementor\Core\Admin\Admin;
use oneTap\Utility\Enqueuer\Enqueuer;
use oneTap\Base\Helper;

class Admin_Asset extends Enqueuer {

    /**
     * Constuctor
     *
     */
    function __construct() {
        $this->asset_group = 'admin';
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_scripts'] );
    }

    /**
     * Load Admin CSS Scripts
     *
     * @return void
     */
    public function load_scripts() {
        $this->add_css_scripts();
        $this->add_js_scripts();
    }

    /**
     * Load Admin CSS Scripts
     *
     * @Example
      $scripts['onetap-settings-panel-admin-style'] = [
          'file_name' => 'admin',
          'base_path' => ONETAP_CSS_PATH,
          'deps'      => [],
          'ver'       => $this->script_version,
          'group'     => 'admin',
      ];
     * 
     * @return void
     */
    public function add_css_scripts() {
        $scripts = [];
        
        $scripts           = array_merge( $this->css_scripts, $scripts );
        $this->css_scripts = $scripts;
    }

    /**
     * Load Admin JS Scripts
     *
     * @Example
      $scripts['onetap-settings-panel-admin-script'] = [
          'file_name' => 'admin',
          'base_path' => ONETAP_JS_PATH,
          'group'     => 'admin',
          'data'      => [ 'object-key' => [] ],
      ];
     * 
     * @return void
     */
    public function add_js_scripts() {
        $scripts = [];

        $scripts['onetap-settings-panel-admin-script'] = [
            'file_name' => 'settings-panel-admin',
            'base_path' => ONETAP_JS_PATH,
            'group'     => 'admin',
            'data'      => [
                'oneTap_SettingsScriptData' => [
                    'apiEndpoint' => rest_url( 'onetap/v1' ),
                    'ajaxURL'     => admin_url( 'admin-ajax.php' ),
                    'apiNonce'    => wp_create_nonce( 'wp_rest' ),
                    'nonce'       => wp_create_nonce( Helper\get_nonce_key() ),
                    'wp_pages'    => [],
                    'wp_roles'    => [],
                    'wp_post_types' => Helper\get_wp_post_types(),
                    'options'     => get_option( 'onetap_options' ),
                    'context'     => [],
                ],
            ],
        ];

        $scripts          = array_merge( $this->js_scripts, $scripts );
        $this->js_scripts = $scripts;
    }
}