<?php

namespace oneTap\Base\Helper;

use Directorist\Asset_Loader\Helper;
use WP_Query;

/**
 * Get The Public Template
 *
 * @param string $path
 * @param array $data
 * @param bool $extract
 *
 * @return string Public Template
 */
function get_template( $path = '', $data = [], $extract = true ) {

    ob_start();

    get_the_template( $path, $data, $extract );

    return ob_get_clean();
}

/**
 * Prints The Public Template
 *
 * @param string $path
 * @param array $data
 * @param bool $extract
 *
 * @return void Prints Public Template
 */
function get_the_template( $path = '', $data = [], $extract = true ) {

    $file_path = ONETAP_TEMPLATE_PATH . $path;

    get_the_file_content( $file_path, $data, $extract );
}


/**
 * Get The Admin Template
 *
 * @param string $path
 * @param array $data
 * @param bool $extract
 *
 * @return string Admin Template
 */
function get_view( $path = '', $data = [], $extract = true ) {

    ob_start();

    get_the_view( $path, $data, $extract );

    return ob_get_clean();
}

/**
 * Prints The Admin Template
 *
 * @param string $path
 * @param array $data
 * @param bool $extract
 *
 * @return void Prints Admin Template
 */
function get_the_view( $path = '', $data = [], $extract = true ) {

    $file_path = ONETAP_VIEW_PATH . $path;

    get_the_file_content( $file_path, $data, $extract );
}

/**
 * Prints The File Content
 *
 * @param string $path
 * @param array $data
 * @param bool $extract
 *
 * @return void Prints the file contents
 */
function get_the_file_content( $path = '', $data = [], $extract = true ) {

    $file = $path . '.php';

    if ( ! file_exists( $file ) ) {
        return;
    }

    if ( $extract ) {
        extract( $data );
    }

    include $file;
}

/**
 * Handle Upload
 *
 * @return mixed
 */
function handle_media_upload( $file, $overrides = array( 'test_form' => false ) ) {
    include_media_uploader_files();

    $time = current_time( 'mysql' );
    $file = wp_handle_upload( $file, $overrides, $time );

    return $file;
}


/**
 * Filter Params
 *
 * @param array $default
 * @param array $args
 *
 * @return array Merged Params
 */
function filter_params( $default = [], $args = [] ) {

    foreach( $args as $key => $value ) {

        if ( ! isset( $default[ $key ] ) ) {
            unset( $args[ $key ] );
        }
    }

    return $args;

}

/**
 * Merge Params
 *
 * @param array $default
 * @param array $args
 *
 * @return array Merged Params
 */
function merge_params( $default = [], $args = [] ) {

    foreach( $default as $key => $value ) {

        if ( ! isset( $args[ $key ] ) ) {
            continue;
        }

        $default[ $key ] = $args[ $key ];
    }

    return $default;

}

/**
 * Is Truthy
 *
 * @param mixed $value
 * @return bool
 */
function is_truthy( $value ) {

    if ( true === $value ) {
        return true;
    }

    if ( 'true' === strtolower( $value ) ) {
        return true;
    }

    if ( 1 === $value ) {
        return true;
    }

    if ( '1' === $value ) {
        return true;
    }

    return false;

}

/**
 * List has same data
 *
 * @param array $list_a
 * @param array $list_b
 *
 * @return bool
 */
function list_has_same_data( $list_a = [], $list_b = [] ) {

    if ( ! is_array( $list_a ) || ! is_array( $list_b ) ) {
        return false;
    }

    if ( count( $list_a ) < count( $list_b ) ) {
        $smaller_list = $list_a;
        $larger_list  = $list_b;
    } else {
        $smaller_list = $list_b;
        $larger_list  = $list_a;
    }

    foreach( $smaller_list as $key => $value ) {

        if ( ! isset( $larger_list[ $key ] ) ) {
            continue;
        }

        if ( (string) $value !== (string) $larger_list[ $key ] ) {
            return false;
        }
    }

    return true;

}

/**
 * Swap array keys
 *
 * @param array $list
 * @param array $swap_map
 *
 * @return array Swaped Array
 */
function swap_array_keys( $list = [], $swap_map = [] ) {

    if ( ! is_array( $list ) && ! is_array( $swap_map ) ) {
        return $list;
    }

    foreach( $list as $key => $value ) {

        if ( empty( $swap_map[ $key ] ) ) {
            continue;
        }

        unset( $list[ $key ] );

        $swap_key = $swap_map[ $key ];
        $list[ $swap_key ] = $value;
    }

    return $list;

}

/**
 * Convert string to int array
 *
 * @param string $string
 * @param string $separator ,
 * @param string $remove_non_int_items true
 *
 * @return array
 */
function convert_string_to_int_array( $string, $separator = ',', $remove_non_int_items = true ) {
    $list = convert_string_to_array( $string, $separator );
    $list = parse_array_items_to_int( $list, $remove_non_int_items );

    return $list;
}

/**
 * Convert string to array
 *
 * @param string $string
 * @param string $separator ,
 *
 * @return array
 */
function convert_string_to_array( $string, $separator = ',' ) {

    $string = trim( $string, ',\s' );
    $list   = explode( $separator, $string );

    if ( ! is_array( $list ) ) {
        return [];
    }

    return $list;
}

/**
 * Parse array items to int
 *
 * @param array $list
 *
 * @return array
 */
function parse_array_items_to_int( $list = [], $remove_non_int_items = true ) {

    if ( ! is_array( $list ) ) {
        return $list;
    }

    foreach( $list as $key => $value ) {

        $list[ $key ] = 0;

        if ( is_numeric( $value ) ) {
            $list[ $key ] = (int) $value;
        }

        if ( ! is_numeric( $value ) && $remove_non_int_items ) {
            unset( $list[ $key ] );
        }

    }

    return array_values( $list );
}

/**
 * Generate Slug
 *
 * @param string $string
 *
 * @return string Slug
 */
function generate_slug( $string ) {

    $slug = trim( $string );
    $slug = sanitize_key( $slug );
    $slug = strtolower( $string );
    $slug = preg_replace( '/\s{2,}/', ' ', $slug );
    $slug = preg_replace( '/\s/', '-', $slug );

    return $slug;

}

/**
 * Delete File by URL
 *
 * @param string $file_url
 * @return bool
 */
function delete_file_by_url( $file_url ) {
    $regex = '/wp-content.+/';

    $match = [];
    preg_match( $regex, $file_url, $match );

    $file_path = ( ! empty( $match ) ) ? $match[0] : '';

    $upload_dir = wp_upload_dir();
    $file_src   = preg_replace( $regex, $file_path, $upload_dir['basedir'] );

    if ( file_exists( $file_src ) ) {
        wp_delete_file( $file_src );

        return true;
    }

    return false;
}

/**
 * Include Media Uploader Files
 *
 * @return void
 */
function include_media_uploader_files() {

    require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
    require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
    require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

}


/**
 * Timezone - helper to retrieve the timezone string for a site until.
 * a WP core method exists (see https://core.trac.wordpress.org/ticket/24730).
 *
 * Adapted from https://secure.php.net/manual/en/function.timezone-name-from-abbr.php#89155.
 *
 * Copied from wc_timezone_string
 *
 * @return string PHP timezone string for the site
 */
function timezone_string() {
	// Added in WordPress 5.3 Ref https://developer.wordpress.org/reference/functions/wp_timezone_string/.
	if ( function_exists( 'wp_timezone_string' ) ) {
		return wp_timezone_string();
	}

	// If site timezone string exists, return it.
	$timezone = get_option( 'timezone_string' );
	if ( $timezone ) {
		return $timezone;
	}

	// Get UTC offset, if it isn't set then return UTC.
	$utc_offset = floatval( get_option( 'gmt_offset', 0 ) );
	if ( ! is_numeric( $utc_offset ) || 0.0 === $utc_offset ) {
		return 'UTC';
	}

	// Adjust UTC offset from hours to seconds.
	$utc_offset = (int) ( $utc_offset * 3600 );

	// Attempt to guess the timezone string from the UTC offset.
	$timezone = timezone_name_from_abbr( '', $utc_offset );
	if ( $timezone ) {
		return $timezone;
	}

	// Last try, guess timezone string manually.
	foreach ( timezone_abbreviations_list() as $abbr ) {
		foreach ( $abbr as $city ) {
			// WordPress restrict the use of date(), since it's affected by timezone settings, but in this case is just what we need to guess the correct timezone.
			if ( (bool) date( 'I' ) === (bool) $city['dst'] && $city['timezone_id'] && intval( $city['offset'] ) === $utc_offset ) { // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				return $city['timezone_id'];
			}
		}
	}

	// Fallback to UTC.
	return 'UTC';
}


/*
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function clean_var($var) {
    if (is_array($var)) {
        return array_map('clean_var', $var);
    } else {
        return is_scalar($var) ? sanitize_text_field($var) : $var;
    }
}

/**
 * Sanitize List Items
 *
 * @param array $list
 * @param array $schema
 *
 * @return array Sanitized List
 */
function sanitize_list_items( $list = [], $schema = [] ) {
    $default_schema = [];

    $default_schema['string']     = [];
    $default_schema['integer']    = [ 'id' ];
    $default_schema['serialized'] = [];
    $default_schema['datetime']   = [ 'created_on', 'updated_on' ];
    $default_schema['boolean']    = [];
    $default_schema['json']       = [];

    $schema = merge_params( $default_schema, $schema );

    // Sanitize Fields
    foreach ( $list as $key => $value ) {

        // Sanitize String Fields
        if ( in_array( $key, $schema['string'] ) ) {
            $list[ $key ] = ( ! empty( $list[ $key ] ) && is_string( $list[ $key ] ) ) ? sanitize_text_field( $list[ $key ] ) : null;
        }

        // Sanitize Integer Fields
        if ( in_array( $key, $schema['integer'] ) ) {
            $list[ $key ] = ( ! empty( $list[ $key ] ) && is_numeric( $list[ $key ] ) ) ? (int) $list[ $key ] : null;
        }

        // Sanitize Boolean Fields
        if ( in_array( $key, $schema['boolean'] ) ) {
            $list[ $key ] = ( ! empty( $list[ $key ] ) && is_truthy( $list[ $key ] ) ) ? true : false;
        }

        // Sanitize Serialized Fields
        else if ( in_array( $key, $schema['serialized'] ) ) {
            $list[ $key ] = ( ! empty( $list[ $key ] ) ) ? maybe_unserialize( $value ) : null;
        }

        // Sanitize JSON Fields
        else if ( in_array( $key, $schema['json'] ) ) {
            $json_data    = json_decode( $list[ $key ], true );
            $list[ $key ] = ( ! empty( $list[ $key ] ) && $json_data  ) ? $json_data : null;
        }

        // Sanitize Date Fields
        else if ( in_array( $key, $schema['datetime'] ) ) {
            $formatted_key = $key . '_formatted';
            $timezone      = ( ! empty( $request_params['timezone'] ) ) ? $request_params['timezone'] : null;

            $list[ $formatted_key ] = ( ! empty( $list[ $key ] ) ) ? esc_html( get_formatted_time( $list[ $key ], $timezone ) ) : null;
        }

        else {
            $list[ $key ] = esc_html( $value );
        }

    }

    return $list;
}

/**
 * Get Formatted Time
 *
 * @param $time
 * @param $timezone
 */
function get_formatted_time( $time, $timezone ) {
    $timezone  = $timezone ? $timezone : wp_timezone_string();
    $timezone  = new \DateTimeZone( $timezone );
    $timestamp = strtotime( $time );

    return wp_date( 'j M y @ G:i', $timestamp, $timezone );
}

/**
 * Get WP Post Types
 *
 * @return array Post Types
 */
function get_wp_post_types() {
	$types = get_post_types( [], 'objects' );
	if ( ! $types ) {
		return [];
	}

	$all_types = [];

	foreach( $types as $key => $type ) {
		$all_types[] = [
			'id'    => $key,
			'title' => $type->label,
		];
	}

	return $all_types;
}

/**
 * Get Options
 *
 * @return array Options
 */
function get_options() {
	return \get_option( ONETAP_OPTIONS, [] );
}

/**
 * Get Option
 *
 * @param string $option_key
 * @param mixed $default
 *
 * @return mixed Option
 */
function get_option( $option_key = '', $default = '' ) {
	$options = get_options();

	if ( empty( $options ) ) {
		return [];
	}

	if ( ! isset( $options[ $option_key ] ) ) {
		return $default;
	}

	return $options[ $option_key ];
}

/**
 * Sets or Update Option
 *
 * @param string $option_key
 * @param mixed $value
 *
 * @return void
 */
function update_option( $option_key = '', $value = '' ) {
	$options = get_options();

	$options[ $option_key ] = $value;

	\update_option( ONETAP_OPTIONS, $options  );
}

/**
 * Sets or Update Options
 *
 * @param array $options
 * @return array $options
 */
function update_options( $new_options = [] ) {
	$old_options = get_options();

	$options = array_merge( $old_options, $new_options );

	\update_option( ONETAP_OPTIONS, $options );

	return $options;
}

/**
 * Sets or Update Option
 *
 * @return void
 */
function delete_option( $option_key = '' ) {
	$options = get_options();

	if ( ! isset( $options[ $option_key ] ) ) {
		return;
	}

	unset( $options[ $option_key ] );

	\update_option( ONETAP_OPTIONS, $options  );
}

/**
 * Sets or Update Option
 *
 * @param array $deleting_options
 * @return array $options
 */
function delete_options( $option_keys = [] ) {
	$options = get_options();

	if ( empty( $options ) ) {
		return;
	}

	foreach ( $option_keys as $key ) {

		if ( ! isset( $options[ $key ] ) ) {
			continue;
		}

		unset( $options[ $key ] );
	}

	\update_option( ONETAP_OPTIONS, $options );

	return $options;
}

/**
 * Generate an unique nonce key using version constant.
 *
 * @since 7.0.6.2
 *
 * @return string nonce key with current version
 */
function get_nonce_key() {
    return 'onetap_nonce_' . ONETAP_VERSION;
}

/**
 * Check if the given nonce field contains a verified nonce.
 *
 * @since 7.0.6.2
 * @since 7.3.1 $action param added
 *
 * @see onetap_get_nonce_key()
 *
 * @param string $nonce_field $_GET or $_POST field name.
 * @param string $action Nonce action key. Default to onetap_get_nonce_key()
 *
 * @return boolen
 */
function verify_nonce( $nonce_field = 'onetap_nonce', $action = '' ) {
    $nonce = ! empty( $_REQUEST[ $nonce_field ] ) ? onetap_clean( wp_unslash( $_REQUEST[ $nonce_field ] ) ) : '';
    return wp_verify_nonce( $nonce, ( $action ? $action : get_nonce_key() ) );
}

/*
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function onetap_clean($var)
{
    if (is_array($var)) {
        return array_map('onetap_clean', $var);
    } else {
        return is_scalar($var) ? sanitize_text_field($var) : $var;
    }
}

function widget_attributes(){
    global $wp;
	$current_url = home_url( add_query_arg( array(), $wp->request ) );
	$login_uri   = home_url().'/?onetap-signin';
    $attr = [
        'auto_select'   => get_option( 'autoSignIn', true ) ? 'true' : 'false',
        'redirect_uri'  => $current_url,
        'cancel_on_tap_outside' => get_option( 'cancelOnTapOutside', false ) ? 'true' : 'false',
        'login_uri' => $login_uri,
    ];

    return $attr;
}

/*
 * Popup guard, check where to show the popup.
 *
 * @param string|page ID to check.
 * @return bolian
 */
function guard( $page = '' )
{
    $excluded_single = get_option( 'excludedSingle' );

    if( $excluded_single ){
        foreach( $excluded_single as $single_post ) {
            if( is_singular( $single_post['id'] ) ){
                return false;
            }
        }
    }
    return true;
    
}