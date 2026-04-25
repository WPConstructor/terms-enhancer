<?php
/** 
 * Bootstrap Class file.
 *
 * This file holds the class of the main bootstrap class
 * which handles the bootstrapping of wpcn Display Term Counts. 
 *
 * @package    WPConstructor\DisplayTermCounts
 * @copyright  2026 by WPConstructor
 * @license    GPL-3.0-or-later http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0.0 
 * @since      1.0.0 
 */

namespace WPConstructor\DisplayTermCounts;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Class Bootstrap
 *
 * The Bootstrap Class of wpcn Display Term Counts.
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class Bootstrap {

	/**
	 * The constructor function of this class.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * The initialization.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init() {
		
		/**
		 * Adds support for the classic theme.
		 */
		add_filter( 'the_tags', array( $this, 'classic_theme_tag_content' ) );

		/**
		 * Enqueue "blocks.js"
		 */
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor' ) );

		/**
		 * Adds Javascript to head.
		 */
		add_action( 'admin_head', array( $this, 'admin_head' ) );

		/**
		 * Loads the render class.
		 */
		add_action(
			'init',
			function () {
				new Render();
			}
		);
	}

	/**
	 * Adds Javascript CSS Style Sheet Var to Head.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_head() {
		if ( true === $this->add_head ) {
			echo '<script>var WTCCssStyleSheet = "' . esc_url( PLUGIN_URL . 'css/editor.css' ) . '";</script>';
		}
	}

	/**
	 * Enqueue "blocks.js".
	 *
	 * Adds the callback for enqueueing the block.js if in block editor.
	 */
	public function enqueue_block_editor() {
		// Enqueue block editor JavaScript file.

		wp_enqueue_script(
			'wpcn-display-term-counts-blocks',
			PLUGIN_URL . 'js/blocks.js',
			array(), /*array( 'wp-blocks', 'wp-editor', 'wp-components', 'react', 'wp-element', 'wp-i18n' ),*/
			VERSION,
			true
		);

		// Enqueue the stylesheet.
		wp_enqueue_style( 'wpcn-display-term-counts-editor-styles', PLUGIN_URL . 'css/editor.css', array(), VERSION );

		$this->add_head = true;
	}

	/**
	 * Adds Javascript CSS Style Sheet Var to Head if true.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	private $add_head = false;

	/**
	 * Class Theme Tag Content
	 *
	 * Changes the content of the tags for classic theme tag content.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @param string $tag_list This is the list of tags for the post. It’s a string that
	 *                         contains the formatted list of tags. Each tag is wrapped
	 *                         in an <a> element.
	 * @param string $before   This is the text to display before the list of tags. It’s a
	 *                         string that is output before the list of tags.
	 * @param string $sep      This is the separator between the tags. It’s a string that is output
	 *                         between each tag in the list.
	 * @param string $after    This is the text to display after the list of tags. It’s a string that is
	 *                         output after the list of tags.
	 * @param int    $post_id  This is the ID of the post. It’s an integer that represents the unique
	 *                         ID of the post.
	 * @return string The tag list.
	 */
	public function classic_theme_tag_content( $tag_list, $before, $sep, $after, $post_id ) {
		$tags = get_the_tags( $post_id );
		if ( $tags ) {
			$tag_list = $before;
			foreach ( $tags as $tag ) {
				$tag_list .= '<a href="' . get_tag_link( $tag ) . '" rel="tag">' . $tag->name . ' (' . $tag->count . ')</a>' . $sep;
			}
			$tag_list = rtrim( $tag_list, $sep ) . $after;
		}
		return $tag_list;
	}
}
