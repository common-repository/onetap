<?php

if ( ! defined( 'ONETAP_VERSION' ) ) {
    define( 'ONETAP_VERSION', '1.1.9' );
}

if ( ! defined( 'ONETAP_PREFIX' ) ) {
    define( 'ONETAP_PREFIX', 'onetap' );
}

if ( ! defined( 'ONETAP_DB_TABLE_PREFIX' ) ) {
    define( 'ONETAP_DB_TABLE_PREFIX', ONETAP_PREFIX );
}

if ( ! defined( 'ONETAP_REST_BASE_PREFIX' ) ) {
    define( 'ONETAP_REST_BASE_PREFIX', ONETAP_PREFIX );
}

if ( ! defined( 'ONETAP_IN_DEVELOPMENT' ) ) {
    define( 'ONETAP_IN_DEVELOPMENT', SCRIPT_DEBUG );
}

if ( ! defined( 'ONETAP_SCRIPT_VERSION' ) ) {
    define( 'ONETAP_SCRIPT_VERSION', ONETAP_VERSION );
}

if ( ! defined( 'ONETAP_FILE' ) ) {
    define( 'ONETAP_FILE', dirname( dirname( __FILE__ ) ) . '/onetap.php' );
}

if ( ! defined( 'ONETAP_BASE' ) ) {
    define( 'ONETAP_BASE', dirname( dirname( __FILE__ ) ) . '/' );
}

if ( ! defined( 'ONETAP_LANGUAGES' ) ) {
    define( 'ONETAP_LANGUAGES', ONETAP_BASE . 'languages' );
}

if ( ! defined( 'ONETAP_POST_TYPE' ) ) {
    define( 'ONETAP_POST_TYPE', 'onetap' );
}

if ( ! defined( 'ONETAP_TEMPLATE_PATH' ) ) {
    define( 'ONETAP_TEMPLATE_PATH', ONETAP_BASE . 'template/' );
}

if ( ! defined( 'ONETAP_VIEW_PATH' ) ) {
    define( 'ONETAP_VIEW_PATH', ONETAP_BASE . 'view/' );
}

if ( ! defined( 'ONETAP_URL' ) ) {
    define( 'ONETAP_URL', plugin_dir_url( ONETAP_FILE ) );
}

if ( ! defined( 'ONETAP_ASSET_URL' ) ) {
    define( 'ONETAP_ASSET_URL', ONETAP_URL . 'assets/' );
}

if ( ! defined( 'ONETAP_ASSET_SRC_PATH' ) ) {
    define( 'ONETAP_ASSET_SRC_PATH', 'src/' );
}

if ( ! defined( 'ONETAP_JS_PATH' ) ) {
    define( 'ONETAP_JS_PATH', ONETAP_ASSET_URL . 'js/' );
}

if ( ! defined( 'ONETAP_VENDOR_JS_PATH' ) ) {
    define( 'ONETAP_VENDOR_JS_PATH',  ONETAP_ASSET_URL . 'js/vendor-js' );
}

if ( ! defined( 'ONETAP_VENDOR_JS_SRC_PATH' ) ) {
    define( 'ONETAP_VENDOR_JS_SRC_PATH', 'assets/vendor-js/' );
}

if ( ! defined( 'ONETAP_CSS_PATH' ) ) {
    define( 'ONETAP_CSS_PATH', ONETAP_ASSET_URL . 'css/' );
}

if ( ! defined( 'ONETAP_LOAD_MIN_FILES' ) ) {
    define( 'ONETAP_LOAD_MIN_FILES', ! ONETAP_IN_DEVELOPMENT );
}

// Meta Keys
if ( ! defined( 'ONETAP_META_PREFIX' ) ) {
    define( 'ONETAP_META_PREFIX',  ONETAP_PREFIX . '_' );
}

if ( ! defined( 'ONETAP_USER_META_AVATER' ) ) {
    define( 'ONETAP_USER_META_AVATER', ONETAP_META_PREFIX . 'user_avater' );
}

if ( ! defined( 'ONETAP_USER_META_IS_GUEST' ) ) {
    define( 'ONETAP_USER_META_IS_GUEST', ONETAP_META_PREFIX . 'is_guest' );
}

if ( ! defined( 'ONETAP_OPTIONS' ) ) {
    define( 'ONETAP_OPTIONS', ONETAP_META_PREFIX . 'options' );
}