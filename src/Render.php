<?php
/** 
 * Render Class file.
 *
 * This file holds the class of the render class which handles
 * the rendering of WPConstructor Terms Enhancer. 
 *
 * @package    WPConstructor\TermsEnhancer
 * @copyright  2026 by WPConstructor
 * @license    GPL-3.0-or-later http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0.0 
 * @since      1.0.0 
 */

namespace WPConstructor\TermsEnhancer;

use DOMDocument;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

/**
 * Class Render
 *
 * The Render Class of WPConstructor Terms Enhancer.
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class Render {

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
	 * Render callback function.
	 *
	 * Renders the "core/post-term" block.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @param string $block_content The content of the block.
	 * @param array  $block The block array.
	 * @return string The block content.
	 */
	public function render( $block_content, $block ) {
		if ( 'core/post-terms' === $block['blockName'] ) {
			d( $block );
			if ( isset( $block['attrs']['displayCounts'] ) && true === $block['attrs']['displayCounts'] ) {
				if ( isset( $block['attrs']['term'] ) ) {
					$term_type = $block['attrs']['term'];
					$terms     = $this->get_text_between_a_tags( $block_content );
					foreach ( $terms as $term ) {
						$counts        = $this->get_used_post_term_tag_count( $term, $term_type );
						$block_content = $this->replace_first_occurrence( '>' . $term . '<', '>' . $term . ' (' . $counts . ')<', $block_content );
					}
				}
			}
		}
		return $block_content;
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
		add_filter( 'render_block', array( $this, 'render' ), 10, 2 );
	}

	/**
	 * Gets the used post term tag counts.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @param string $term_name The term name.
	 * @param string $term_type The term slug/type.
	 * @return int The amount of used terms to the name.
	 */
	private function get_used_post_term_tag_count( $term_name, $term_type ) {
		$term = get_term_by( 'name', $term_name, $term_type );
		if ( ! $term || is_wp_error( $term ) ) {
			return 0;
		}
		$count = isset( $term->count ) ? $term->count : 0;
		return $count;
	}

	/**
	 * Gets the text betweeen a tags.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @param string $html The html to get the a tags.
	 * @return array The texts of the a tags.
	 */
	private function get_text_between_a_tags( $html ) {

		if ( trim( $html ) === '' ) {
			return array();
		}

		$dom = new DOMDocument();

		// Suppress errors caused by invalid HTML.
		libxml_use_internal_errors( true );
		$dom->loadHTML( $html );
		$texts   = array();
		$anchors = $dom->getElementsByTagName( 'a' );

		foreach ( $anchors as $anchor ) {
			// phpcs:ignore
            $text = $anchor->nodeValue;
			$texts[] = $text;
		}
		return $texts;
	}

	/**
	 * Replaces string with first occurrence.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @param string $search The string to search.
	 * @param string $replace The string to replace.
	 * @param string $subject The subject to replace.
	 * @return string The replaced string subject.
	 */
	private function replace_first_occurrence( $search, $replace, $subject ) {
		$pos = strpos( $subject, $search );

		if ( false !== $pos ) {
			$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
		}
		return $subject;
	}
}
