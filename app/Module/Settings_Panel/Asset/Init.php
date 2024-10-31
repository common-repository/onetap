<?php

namespace oneTap\Module\Settings_Panel\Asset;

use oneTap\Helper;

class Init {

    /**
     * Constuctor
     *
     */
    function __construct() {

        // Register Enqueuers
        $enqueuers = $this->get_assets_enqueuers();
        Helper\Serve::register_services( $enqueuers );

    }

    private function get_assets_enqueuers() {
        return [
            Admin_Asset::class,
        ];
    }
}