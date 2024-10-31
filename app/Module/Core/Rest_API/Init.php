<?php

namespace oneTap\Module\Core\Rest_API;

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
     * @return array Controllers
     */
    protected function get_controllers() {
        return [
            Rest_Filters::class,
            Version_1\Init::class,
        ];
    }

}