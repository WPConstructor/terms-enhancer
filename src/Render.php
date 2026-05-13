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

// phpcs:disable WordPress.Files.FileName.InvalidClassFileName,WordPress.Files.FileName

namespace WPConstructor\TermsEnhancer;

use DOMDocument;

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
	 * @param bool $init Init hooks.
	 * @return void
	 */
	public function __construct( bool $init = false ) {
		if ( $init ) {
			$this->init();
		}
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
	public function render( string $block_content, array $block ): string {
		if ( 'core/post-terms' !== ( $block['blockName'] ?? '' ) ) {
			return $block_content;
		}

		if (
			empty( $block['attrs']['displayCounts'] ) ||
			empty( $block['attrs']['term'] )
		) {
			return $block_content;
		}

		$term_type = $block['attrs']['term'];

		libxml_use_internal_errors( true );

		$dom = new \DOMDocument();
		$dom->loadHTML(
			mb_convert_encoding( $block_content, 'HTML-ENTITIES', 'UTF-8' )
		);

		$xpath = new \DOMXPath( $dom );

		$links = $xpath->query( '//a' );

		if ( ! $links || 0 === $links->length ) {
			return $block_content;
		}

		$x = 0;

		foreach ( $links as $link ) {
			++$x;

			// phpcs:ignore
			$term = trim( $link->textContent );
			if ( '' === $term ) {
				continue;
			}

			$counts = $this->get_used_post_term_tag_count( $term, $term_type );

			// Append count safely.
			// phpcs:ignore
			$link->nodeValue = $term . ' (' . $counts . ')';

			// Disable only if count === 1.
			if ( 1 === $counts ) {
				$existing_style = $link->getAttribute( 'style' );

				$new_style = 'cursor:not-allowed';

				if ( '' !== trim( $existing_style ) ) {
					// Ensure proper spacing between declarations.
					$new_style = rtrim( $existing_style, ';' ) . '; ' . $new_style;
				}

				$link->setAttribute( 'style', $new_style );
			}
		}

		$html = $dom->saveHTML();

		// Optional cleanup: remove doctype/html/body wrappers added by DOMDocument.
		$body = $dom->getElementsByTagName( 'body' )->item( 0 );

		$result = '';
		// phpcs:ignore
		foreach ( $body->childNodes as $child ) {
			$result .= $dom->saveHTML( $child );
		}

		return $result;
	}

	/**
	 * The initialization.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init(): void {
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
	protected function get_used_post_term_tag_count( string $term_name, string $term_type ): int {
		$term = get_term_by( 'name', $term_name, $term_type );
		if ( ! $term || is_wp_error( $term ) ) {
			return 0;
		}
		$count = isset( $term->count ) ? $term->count : 0;
		return $count;
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
	private function replace_first_occurrence( string $search, string $replace, string $subject ): string {
		$pos = strpos( $subject, $search );

		if ( false !== $pos ) {
			$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
		}
		return $subject;
	}
}
