<?php

namespace oneTap\Module\Core\Rest_API\Version_1;

use oneTap\Module\Core\Rest_API\Base;

abstract class Rest_Base extends Base {

    /**
     * @var string
     */
    public $namespace = ONETAP_REST_BASE_PREFIX . '/v1';

}
