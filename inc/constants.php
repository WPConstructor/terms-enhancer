<?php
/** 
 * WPConstructor Terms Enhancer Constants Class file.
 *
 * This file sets the constants of the WPConstructor Terms Enhancer plugin. 
 *
 * @package    WPConstructor\TermsEnhancer
 * @copyright  (c) 2024 by WPConstructor
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @version    1.0.0 
 * @since      1.0.0 
 */

namespace WPConstructor\TermsEnhancer;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// Gets plugin version.
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$plugin_data    = get_plugin_data( MAIN_FILE );
$plugin_version = $plugin_data['Version'];

// Sets plugin dirs of the parent dir.
define( __NAMESPACE__ . '\\PLUGIN_DIR', plugin_dir_path( MAIN_FILE ) );

// Sets plugin version.
define( __NAMESPACE__ . '\\VERSION', $plugin_version );

/**
 * Init action callback.
 *
 * Defines Constants.
 *
 * Please note that the plugins_url() function should not be called in the global context of plugins,
 * but rather in a hook like init or admin_init to ensure that the plugins_url filters are already hooked
 * at the time the function is called.
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 * @return void
 */
function init_callback() {
	// Gets plugin url of the parent dir.
	define( __NAMESPACE__ . '\\PLUGIN_URL', plugin_dir_url( MAIN_FILE ) );
}

// Defines PLUGIN_URL constant when init is ran.
add_action( 'init', __NAMESPACE__ . '\\init_callback' );
