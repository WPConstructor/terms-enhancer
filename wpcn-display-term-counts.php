<?php
/** 
 * WPConstructor Display Term Counts main file.
 *
 * This file handles the entry point of the WPConstructor Display
 * Term Counts plugin. It sets up a class autoloading and
 * calls the \WPConstructor\DisplayTermCounts\Bootstrap Class
 * which handles the bootstraping of this plugin.
 *
 * @package    WPConstructor\DisplayTermCounts
 * @copyright  2026 by WPConstructor
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @version    1.0.0 
 * @since      1.0.0 
 */

/*
 * Plugin Name: WPConstructor Display Term Counts
 * Plugin URI: https://wpconstructor.com/wpconstructor-display-term-counts/
 * Description: WPConstructor Display Term Counts is a WordPress plugin that displays the total number of terms within the post terms block or in classic theme templates.
 * Version: 0.1.0
 * Requires at least: 5.0
 * Requires PHP: 5.6.20
 * Author: WPConstructor
 * Author URI: https://webconstruction.ch
 * License: GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: /languages
*/

namespace WPConstructor\DisplayTermCounts;

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
		if ( strtolower( 'WPConstructor' ) === $pieces[0] && strtolower( 'DisplayTermCounts' ) === $pieces[1] ) {
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
