<?php

namespace oneTap\Module\Core\Asset;

use oneTap\Utility\Enqueuer\Enqueuer;

class Public_Asset extends Enqueuer {

    /**
     * Constuctor
     *
     */
    function __construct() {
        $this->asset_group = 'public';
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );
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
     * Load Public CSS Scripts
     *
     * @Example
      $scripts['exlac-customer-support-app-core-public-style'] = [
          'file_name' => 'public',
          'base_path' => ONETAP_CSS_PATH,
          'deps'      => [],
          'ver'       => $this->script_version,
          'group'     => 'public',
      ];
     * 
     * @return void
     */
    public function add_css_scripts() {
        $scripts = [];

        // $scripts['exlac-customer-support-app-public-main-style'] = [
        //     'file_name' => 'public-main',
        //     'base_path' => ONETAP_CSS_PATH,
        //     'deps'      => [],
        //     'ver'       => $this->script_version,
        //     'group'     => 'public',
        // ];

        $scripts['exlac-customer-support-app-core-public-style'] = [
            'file_name' => 'core-public',
            'base_path' => ONETAP_CSS_PATH,
            'deps'      => [],
            'ver'       => $this->script_version,
            'group'     => 'public',
        ];

        $scripts           = array_merge( $this->css_scripts, $scripts );
        $this->css_scripts = $scripts;
    }

    /**
     * Load Public JS Scripts
     *
     * @Example
      $scripts['exlac-customer-support-app-core-public-script'] = [
          'file_name' => 'public',
          'src_path'  => ONETAP_ASSET_SRC_PATH . 'modules/core/js/public/',
          'base_path' => ONETAP_JS_PATH,
          'group'     => 'public',
          'data'      => [ 'object-key' => [] ],
      ];
     * 
     * @return void
     */
    public function add_js_scripts() {
        $scripts = [];
        
        $scripts['exlac-customer-support-app-core-public-script'] = [
            'file_name' => 'core-public',
            'src_path'  => ONETAP_ASSET_SRC_PATH . 'modules/core/js/public/',
            'base_path' => ONETAP_JS_PATH,
            'group'     => 'public',
            'data'      => [
                'oneTap_CoreScriptData' => [
                    'apiEndpoint'   => rest_url( 'exlac_cs/v1' ),
                    'apiNonce'      => wp_create_nonce( 'wp_rest' ),
                    'currentPageID' => get_the_ID(),
                    'isFrontPage'   => is_front_page(),
                    'isHome'        => is_home(),
                ],
            ],
        ];

        $scripts          = array_merge( $this->js_scripts, $scripts );
        $this->js_scripts = $scripts;
    }
}