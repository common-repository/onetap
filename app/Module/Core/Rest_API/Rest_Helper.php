<?php

namespace oneTap\Module\Core\Rest_API;

use oneTap\Helper\Date_Time;
use oneTap\Base\Helper;
use \DateTimeZone;
use \WP_Error;

class Rest_Helper {

    /**
     * Check permissions of users on REST API.
     *
     * Copied from wc_rest_check_user_permissions
     *
     * @param string $context   Request context.
     * @param int    $object_id Post ID.
     * @return bool
     */
    public static function check_user_permissions( $context = 'read', $object_id = 0 ) {
        $contexts = array(
            'read'   => 'edit_user',
            'create' => 'promote_users',
            'edit'   => 'edit_user',
            'delete' => 'delete_users',
            'batch'  => 'promote_users',
        );

        $permission = current_user_can( $contexts[ $context ], $object_id );

        return apply_filters( 'exlac_customer_support_app_rest_check_permissions', $permission, $context, $object_id, 'user' );
    }

    /**
     * Parses and formats a date for ISO8601/RFC3339.
     *
     * Required WP 4.4 or later.
     * See https://developer.wordpress.org/reference/functions/mysql_to_rfc3339/
     * @see wc_rest_prepare_date_response
     *
     * @param  string|null|Date_Time $date Date.
     * @param  bool                    $utc  Send false to get local/offset time.
     * @return string|null ISO8601/RFC3339 formatted datetime.
     */
    public static function prepare_date_response( $date, $utc = true ) {
        if ( is_numeric( $date ) ) {
            $date = new Date_Time( "@$date", new DateTimeZone( 'UTC' ) );
            $date->setTimezone( new DateTimeZone( Helper\timezone_string() ) );
        } elseif ( is_string( $date ) ) {
            $date = new Date_Time( $date, new DateTimeZone( 'UTC' ) );
            $date->setTimezone( new DateTimeZone( Helper\timezone_string() ) );
        }

        if ( ! is_a( $date, 'oneTap\Helper\Date_Time' ) ) {
            return null;
        }

        // Get timestamp before changing timezone to UTC.
        return gmdate( 'Y-m-d\TH:i:s', $utc ? $date->getTimestamp() : $date->getOffsetTimestamp() );
    }

    /**
     * Upload image from URL.
     *
     * Copied from wc_rest_upload_image_from_url
     *
     * @param string $image_url Image URL.
     * @return array|WP_Error Attachment data or error message.
     */
    public static function upload_image_from_url( $image_url ) {
        $parsed_url = wp_parse_url( $image_url );

        // Check parsed URL.
        if ( ! $parsed_url || ! is_array( $parsed_url ) ) {
            /* translators: %s: image URL */
            return new WP_Error( 'exlac_customer_support_app_rest_invalid_image_url', sprintf( __( 'Invalid URL %s.', 'directorist' ), $image_url ), array( 'status' => 400 ) );
        }

        // Ensure url is valid.
        $image_url = esc_url_raw( $image_url );

        // download_url function is part of wp-admin.
        if ( ! function_exists( 'download_url' ) ) {
            include_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $file_array         = array();
        $file_array['name'] = basename( current( explode( '?', $image_url ) ) );

        // Download file to temp location.
        $file_array['tmp_name'] = download_url( $image_url );

        // If error storing temporarily, return the error.
        if ( is_wp_error( $file_array['tmp_name'] ) ) {
            return new WP_Error(
                'exlac_customer_support_app_rest_invalid_remote_image_url',
                /* translators: %s: image URL */
                sprintf( __( 'Error getting remote image %s.', 'directorist' ), $image_url ) . ' '
                /* translators: %s: error message */
                . sprintf( __( 'Error: %s', 'directorist' ), $file_array['tmp_name']->get_error_message() ),
                array( 'status' => 400 )
            );
        }

        // Do the validation and storage stuff.
        $file = wp_handle_sideload(
            $file_array,
            array(
                'test_form' => false,
                'mimes'     => self::allowed_image_mime_types(),
            ),
            current_time( 'Y/m' )
        );

        if ( isset( $file['error'] ) ) {
            @unlink( $file_array['tmp_name'] ); // @codingStandardsIgnoreLine.

            /* translators: %s: error message */
            return new WP_Error( 'exlac_customer_support_app_rest_invalid_image', sprintf( __( 'Invalid image: %s', 'directorist' ), $file['error'] ), array( 'status' => 400 ) );
        }

        do_action( 'exlac_customer_support_app_rest_api_uploaded_image_from_url', $file, $image_url );

        return $file;
    }

    /**
     * Returns image mime types users are allowed to upload via the API.
     *
     * Copied from wc_rest_allowed_image_mime_types
     *
     * @return array
     */
    public static function allowed_image_mime_types() {
        return apply_filters(
            'exlac_customer_support_app_rest_allowed_image_mime_types',
            array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                // 'gif'          => 'image/gif',
                'png'          => 'image/png',
                // 'bmp'          => 'image/bmp',
                // 'tiff|tif'     => 'image/tiff',
                // 'ico'          => 'image/x-icon',
            )
        );
    }

}