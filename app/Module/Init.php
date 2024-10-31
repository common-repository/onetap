<?php

namespace oneTap\Module;

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
            Core\Init::class,
            Login_Widget\Init::class,
            Settings_Panel\Init::class,
        ];
    }

}