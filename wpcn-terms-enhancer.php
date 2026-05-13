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

// Require Composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Sets plugin dirs of the parent dir.
define( __NAMESPACE__ . '\\MAIN_FILE', __FILE__ );

// Requires and sets the constants used in this plugin.
require_once __DIR__ . '/src/constants.php';

new Bootstrap();
