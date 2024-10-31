<?php

namespace oneTap\Utility\Enqueuer;

abstract class Enqueuer_Base {

    /**
     * @var array
     */
    public $js_scripts = [];
    /**
     * @var array
     */
    public $css_scripts = [];
    /**
     * @var mixed
     */
    public $script_version = false;
    /**
     * @var mixed
     */
    public $load_min = true;

    /**
     * Register CSS Scripts
     *
     * @return void
     */
    public function register_css_scripts( array $args = [] ) {
        $default = ['scripts' => $this->css_scripts];
        $args    = array_merge( $default, $args );

        foreach ( $args['scripts'] as $handle => $script_args ) {
            $default = [
                'file_name' => $handle,
                'base_path' => ONETAP_CSS_PATH,
                'deps'      => [],
                'ver'       => $this->script_version,
                'media'     => 'all',
                'link'      => '',
            ];

            $script_args = array_merge( $default, $script_args );
            $src         = $script_args['base_path'] . $this->get_script_file_name( $script_args ) . '.css';

            if ( ! empty( $script_args['link'] ) ) {
                $src = $script_args['link'];
            }

            wp_register_style( $handle, $src, $script_args['deps'], $script_args['ver'], $script_args['media'] );
        }

    }

    /**
     * Enqueue CSS Scripts
     *
     * @return void
     */
    public function enqueue_css_scripts_by_group( array $args = [] ) {

        $default = ['scripts' => $this->css_scripts, 'group' => 'public'];
        $args    = array_merge( $default, $args );

        foreach ( $args['scripts'] as $handle => $script_args ) {

            if ( ! $this->can_enqueue_asset( $handle, $script_args, $args ) ) {
                continue;
            }

            wp_enqueue_style( $handle );
        }

    }

    /**
     * Register JS Scripts by Group
     *
     * @param array $args
     * @return void
     */
    public function register_js_scripts( array $args = [] ) {
        $default = ['scripts' => $this->js_scripts];
        $args    = array_merge( $default, $args );

        foreach ( $args['scripts'] as $handle => $script_args ) {
            $default = [
                'file_name' => $handle,
                'base_path' => ONETAP_JS_PATH,
                'link'      => '',
                'deps'      => [],
                'ver'       => $this->script_version,
                'in_footer' => true,
                'src_ext'   => 'js',
            ];

            $script_args = array_merge( $default, $script_args );
            $ext         = 'js';

            $src = $script_args['base_path'] . $this->get_script_file_name( $script_args ) . '.' . $ext;

            if ( ! empty( $script_args['link'] ) ) {
                $src = $script_args['link'];
            }

            wp_register_script( $handle, $src, $script_args['deps'], $script_args['ver'], $script_args['in_footer'] );
        }

    }

    /**
     * Enqueue JS Scripts
     *
     * @return void
     */
    public function enqueue_js_scripts_by_group( array $args = [] ) {
        $default = ['scripts' => $this->js_scripts, 'group' => 'public'];
        $args    = array_merge( $default, $args );

        foreach ( $args['scripts'] as $handle => $script_args ) {

            if ( ! $this->can_enqueue_asset( $handle, $script_args, $args ) ) {
                continue;
            }

            if ( ! empty( $script_args['before_enqueue'] ) ) {
                $this->handle_script_before_enqueue_task( $script_args['before_enqueue'] );
            }

            wp_enqueue_script( $handle );
            $this->add_localize_data_to_script( $handle, $script_args );
        }

    }

// can_enqueue_asset
    /**
     * @param $script_id
     * @param array $script_args
     * @param array $group_args
     */
    public function can_enqueue_asset( $script_id = '', $script_args = [], $group_args = [] ) {

        $in_group = true;

        if ( isset( $script_args['group'] ) ) {

            if ( is_string( $script_args['group'] ) && ( $group_args['group'] !== $script_args['group'] ) ) {
                $in_group = false;
            }

            if ( is_array( $script_args['group'] ) && ! in_array( $group_args['group'], $script_args['group'] ) ) {
                $in_group = false;
            }

            if ( is_string( $script_args['group'] ) && 'global' === $script_args['group'] ) {
                $in_group = true;
            }

            if ( is_array( $script_args['group'] ) && in_array( 'global', $script_args['group'] ) ) {
                $in_group = true;
            }

        }

        if ( ! $in_group ) {
            return false;
        }

        if (  ( ! empty( $script_args['fource_enqueue'] ) || ! empty( $group_args['fource_enqueue'] ) ) ) {
            return true;
        }

        if (  ( isset( $script_args['enable'] ) && false === $script_args['enable'] ) ) {
            return false;
        }

        if (  ( isset( $group_args['page'] ) && isset( $script_args['page'] ) ) ) {

            if ( is_string( $script_args['page'] ) && $group_args['page'] !== $script_args['page'] ) {
                return false;
            }

            if ( is_array( $script_args['page'] ) && ! in_array( $group_args['page'], $script_args['page'] ) ) {
                return false;
            }

        }

        return true;
    }

    /**
     * Handle script before enqueue task
     * 
     * @param array $task
     */
    public function handle_script_before_enqueue_task( $task = [] ) {

        if ( is_array( $task ) && ! $this->is_assoc_array( $task ) && ( count( $task ) > 1 ) ) {
            $class_name  = $task[0];
            $method_name = $task[1];
            $args        = ( isset( $task[2] ) ) ? $task[2] : '';

            if ( class_exists( $class_name ) && method_exists( $class_name, $method_name ) ) {
                $class = new $class_name;
                $class->$method_name( $args );
            }

        }

    }

    /**
     *  Add localize data to script
     *
     * @param string $handle
     * @param array $script_args
     * @return void
     */
    public function add_localize_data_to_script( $handle, $script_args ) {

        if ( ! is_array( $script_args ) ) {return;}

        if ( empty( $script_args['data'] ) ) {return;}

        if ( ! is_array( $script_args['data'] ) ) {return;}

        if ( ! $this->is_assoc_array( $script_args['data'] ) ) {return;}

        
        foreach ( $script_args['data'] as $script_key => $script_data ) {
            wp_localize_script( $handle, $script_key, $script_data );
        }
    }

    /**
     * Is Associative Array
     *
     * @param array $args
     * @return bool
     */
    public function is_assoc_array( $arr = [] ) {

        if ( [] === $arr ) {return false;}

        return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
    }

    /**
     * Get Script File Name
     *
     * @param array $args
     * @return $file_name
     */
    public function get_script_file_name( array $args = [] ) {
        $default = ['has_min' => true, 'has_rtl' => true];
        $args    = array_merge( $default, $args );

        $file_name = ( ! empty( $args['file_name'] ) ) ? $args['file_name'] : '';

        $has_min = ( ! empty( $args['has_min'] ) ) ? true : false;
        $has_rtl = ( ! empty( $args['has_rtl'] ) ) ? true : false;

        $is_rtl  = is_rtl();
        $src_ext = isset( $args['src_ext'] ) ? $args['src_ext'] : 'css';
        if ( $has_rtl && $is_rtl && ( 'css' === $src_ext ) ) {
            $file_name = "{$file_name}.rtl";
        }

        if ( $has_min && $this->load_min ) {
            $file_name = "{$file_name}.min";
        }

        return $file_name;
    }

}
