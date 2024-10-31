<?php

namespace oneTap\Module\Core;

use oneTap\Helper;

class Init {

    /**
     * Constuctor
     *
     * @return void
     */
    public function __construct() {

        // Register Controllers
        $controllers = $this->get_controllers();
        Helper\Serve::register_services( $controllers );

    }

    /**
     * Controllers
     *
     * @return array
     */
    protected function get_controllers() {
        return [
            Asset\Init::class,
            Admin\Init::class,
            Rest_API\Init::class,
        ];
    }

}