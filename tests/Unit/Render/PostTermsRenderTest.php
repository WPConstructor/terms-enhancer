<?php
/**
 * PostTermsRenderTest Class file.
 *
 * This file contains the unit tests for the Render class of the WPConstructor Terms Enhancer plugin.
 *
 * @package    WPConstructor\TermsEnhancer
 * @copyright  2024 by WPConstructor
 * @license    GPL-3.0-or-later
 * @version    1.0.0
 * @since      1.0.0
 */

use PHPUnit\Framework\TestCase;
use WPConstructor\TermsEnhancer\Render;

/**
 * Class PostTermsRenderTest
 *
 * Unit tests for the Render class.
 *
 * @version 1.0.0
 * @since 1.0.0
 */
class PostTermsRenderTest extends TestCase {

	/**
	 * Test that render adds counts and disables terms with a single count.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_render_adds_counts_and_disables_single_count_terms(): void {
		$html = '
<div class="taxonomy-post_tag wp-block-post-terms">

  <a href="http://localhost/wpcon-dev/blogs/tags/journal/" rel="tag">Journal</a>
  <span class="wp-block-post-terms__separator"></span>

  <a href="http://localhost/wpcon-dev/blogs/tags/test/" rel="tag" style="color:red">Test</a>
  <span class="wp-block-post-terms__separator"></span>

  <a href="http://localhost/wpcon-dev/blogs/tags/wpcontructor-com/" rel="tag">WPContructor.com</a>

</div>';

		$mock = $this->getMockBuilder( Render::class )
			->onlyMethods( array( 'get_used_post_term_tag_count' ) )
			->getMock();

		/**
		 * Fake counts:
		 * Journal => 2
		 * Test => 1 (must become disabled + style merged)
		 * WPContructor.com => 3
		 */
		$mock->method( 'get_used_post_term_tag_count' )
			->willReturnMap(
				array(
					array( 'Journal', 'blogs', 2 ),
					array( 'Test', 'blogs', 1 ),
					array( 'WPContructor.com', 'blogs', 3 ),
				)
			);

		$block = array(
			'blockName' => 'core/post-terms',
			'attrs'     => array(
				'displayCounts'     => true,
				'removeSingleLinks' => true,
				'term'              => 'blogs',
			),
		);

		$result = $mock->render( $html, $block );

		// -----------------------------
		// 1. Counts are appended
		// -----------------------------
		$this->assertStringContainsString( 'Journal (2)', $result );
		$this->assertStringContainsString( 'Test (1)', $result );
		$this->assertStringContainsString( 'WPContructor.com (3)', $result );

		// -----------------------------
		// 2. Only "Test" is disabled
		// -----------------------------
		$this->assertStringContainsString( 'cursor:not-allowed', $result );

		// -----------------------------
		// 3. Existing style preserved + merged
		// -----------------------------
		$this->assertStringContainsString( 'color:red', $result );
		$this->assertStringContainsString( 'color:red; cursor:not-allowed', $result );

		// -----------------------------
		// 4. Non-single-count links are NOT disabled
		// -----------------------------
		$this->assertStringNotContainsString(
			'Journal (2)</a><a style="cursor:not-allowed"',
			$result
		);

		// -----------------------------
		// 5. Structure still valid
		// -----------------------------
		$this->assertStringContainsString( '<a', $result );
		$this->assertStringContainsString( '</div>', $result );
	}

	/**
	 * Test that render returns original HTML when the block is not a post-terms block.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_render_returns_original_when_block_is_not_post_terms(): void {
		$mock = $this->getMockBuilder( Render::class )
			->onlyMethods( array( 'get_used_post_term_tag_count' ) )
			->getMock();

		$html = '<a href="#">Test</a>';

		$block = array(
			'blockName' => 'core/other-block',
			'attrs'     => array(
				'displayCounts' => true,
				'term'          => 'blogs',
			),
		);

		$result = $mock->render( $html, $block );

		$this->assertSame( $html, $result );
	}

	/**
	 * Test that render returns original HTML when no links exist in the content.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_render_returns_original_when_no_links_exist(): void {
		$mock = $this->getMockBuilder( Render::class )
			->onlyMethods( array( 'get_used_post_term_tag_count' ) )
			->getMock();

		$html = '<div>No links here</div>';

		$block = array(
			'blockName' => 'core/post-terms',
			'attrs'     => array(
				'displayCounts' => true,
				'term'          => 'blogs',
			),
		);

		$result = $mock->render( $html, $block );

		$this->assertSame( $html, $result );
	}

	/**
	 * Test that render disables terms with a single count when display counts disabled.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function test_render_removes_link_when_single_count_and_display_counts_disabled(): void {
		$html = '
<div class="taxonomy-post_tag wp-block-post-terms">

  <a href="http://localhost/wpcon-dev/blogs/tags/journal/" rel="tag">Journal</a>
  <span class="wp-block-post-terms__separator"></span>

  <a href="http://localhost/wpcon-dev/blogs/tags/test/" rel="tag">Test</a>

</div>';

		$mock = $this->getMockBuilder( Render::class )
		->onlyMethods( array( 'get_used_post_term_tag_count' ) )
		->getMock();

		/**
		 * Only Test has count = 1
		 * Journal has count = 2
		 */
		$mock->method( 'get_used_post_term_tag_count' )
		->willReturnMap(
			array(
				array( 'Journal', 'blogs', 2 ),
				array( 'Test', 'blogs', 1 ),
			)
		);

		$block = array(
			'blockName' => 'core/post-terms',
			'attrs'     => array(
				'displayCounts'     => false,
				'removeSingleLinks' => true,
				'term'              => 'blogs',
			),
		);

		$result = $mock->render( $html, $block );

		// -----------------------------
		// 1. Journal stays as link (count > 1)
		// -----------------------------
		$this->assertStringContainsString( 'Journal', $result );
		$this->assertStringContainsString( '<a', $result );

		// -----------------------------
		// 2. Test link is removed (no <a> wrapping it)
		// -----------------------------
		$this->assertStringContainsString( 'Test', $result );
		$this->assertStringNotContainsString( 'href="http://localhost/wpcon-dev/blogs/tags/test/"', $result );

		// -----------------------------
		// 3. Structure still valid
		// -----------------------------
		$this->assertStringContainsString( '<div', $result );
		$this->assertStringContainsString( '</div>', $result );
	}
}
