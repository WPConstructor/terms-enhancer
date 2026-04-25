<?php
/**
 * Plugin Name: WPConstructor Terms Enhancer
 * Plugin URI: https://wpconstructor.com/wpconstructor-terms-enhancer/
 * Description: Enhance the WordPress Post Terms block with term counts, smart link control, and display improvements for better taxonomy UX.
 * Version: 0.2.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: WPConstructor
 * Author URI: https://webconstruction.ch
 * License: GPL-3.0-or-later http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: /languages
 *
 * @package    WPConstructor\TermsEnhancer
 */

/** 
 * WPConstructor Terms Enhancer main file.
 *
 * This file handles the entry point of the WPConstructor Terms Enhancer plugin.
 * It sets up a class autoloading and calls the \WPConstructor\TermsEnhancer\Bootstrap Class
 * which handles the bootstraping of this plugin.
 *
 * @copyright  2026 by WPConstructor
 * @license    GPL-3.0-or-later http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0.0 
 * @since      1.0.0 
 */

namespace WPConstructor\TermsEnhancer;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// Sets plugin dirs of the parent dir.
define( __NAMESPACE__ . '\\MAIN_FILE', __FILE__ );

// Requires and sets the constants used in this plugin.
require_once __DIR__ . '/inc/constants.php';

/**
 * Registers a custom class autoloader function.
 *
 * @param callable $autoload_function The autoload function to register.
 * @param bool $throw                 Whether to throw exceptions if the autoload function cannot load the requested class.
 *                                    Defaults to true.
 * @param bool $prepend               Whether to prepend the autoloader to the stack instead of appending it.
 *                                    Defaults to false, meaning the autoloader will be appended.
 *
 * @version 1.0.0
 * @since 1.0.0
 *
 * @return bool Returns true on success or false on failure.
 */
spl_autoload_register(
	/**
	 * Auto loads class.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @param string $required_class The class name.
	 * @return bool Returns true on success or false on failure.
	 */
	function ( $required_class ) {
		$required_class = strtolower( str_replace( '\\', '/', $required_class ) );
		$pieces         = explode( '/', $required_class );
		if ( strtolower( 'WPConstructor' ) === $pieces[0] && strtolower( 'TermsEnhancer' ) === $pieces[1] ) {
			unset( $pieces[0] );
			unset( $pieces[1] );
			$pieces = array_values( $pieces );
		} else {
			return false;
		}
		$pieces[ count( $pieces ) - 1 ] = 'class-' . str_replace( '_', '-', $pieces[ count( $pieces ) - 1 ] ) . '.php';

		$relative_path = implode( '/', $pieces );
		$path          = plugin_dir_path( __FILE__ ) . 'classes/' . $relative_path;

		if ( file_exists( $path ) ) {
			require_once $path;
			return true;
		}
		return false;
	},
	true,
	true
);

new Bootstrap();
