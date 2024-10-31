<?php

namespace oneTap\Module\Core\Rest_API;


class Rest_Filters {

    /**
     * Constructor
     * 
     * @return void
     */
    public function __construct() {
        
        add_filter( 'exlac_customer_support_app_rest_check_permissions', [ $this, 'allow_read_context_permission' ], 20, 4 );
        
    }

    /**
     * Allow read context permission
     * 
     * @param boolen $permission
     * @param string $context
     * @param integer $object_id
     * @param string @object_type
     *
     * @return boolen
     */
    public function allow_read_context_permission( $permission, $context, $object_id, $object_type ) {
        if ( $context === 'read' ) {
            $permission = true;
        }
    
        if ( $context === 'create' && $object_type === 'user' ) {
            $permission = true;
        }
    
        return $permission;
    }
    
}
