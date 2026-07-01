<?php
/**
 * Plugin Name: GrowthPress Hub
 * Plugin URI: https://growthpress.io
 * Description: Strategic License Authority and Ecosystem Generation Hub for GrowthPress.
 * Version: 6.3.0 Elite
 * Author: GrowthPress Team
 * Text Domain: growthpress-hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

define( 'GROWTHPRESS_HUB_VERSION', '6.3.0' );
define( 'GROWTHPRESS_HUB_PATH', plugin_dir_path( __FILE__ ) );
define( 'GROWTHPRESS_HUB_URL', plugin_dir_url( __FILE__ ) );

function growthpress_hub_load_modules() {
    $files = array(
        'includes/class-growthpress-license.php',
        'includes/class-growthpress-sample-data.php',
        'admin/class-growthpress-hub-admin.php',
    );

    foreach ( $files as $file ) {
        if ( file_exists( GROWTHPRESS_HUB_PATH . $file ) ) {
            require_once GROWTHPRESS_HUB_PATH . $file;
        }
    }
}
add_action( 'plugins_loaded', 'growthpress_hub_load_modules' );
