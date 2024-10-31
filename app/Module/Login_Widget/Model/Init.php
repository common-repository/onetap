<?php

namespace oneTap\Module\Login_Widget\Model;

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
            One_Tap_Widget::class,
            Controller::class,
        ];
    }
}