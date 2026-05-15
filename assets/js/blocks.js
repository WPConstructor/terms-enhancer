/**
 * WPConstructor - Extension for core/post-terms
 *
 * @package
 * @license GPL-3.0-or-later
 * @version 1.0.0
 * @since 1.0.0
 *
 * @param {Object} wp The wp object.
 */

(function (wp) {
	const { addFilter } = wp.hooks;
	const { createHigherOrderComponent } = wp.compose;
	const { InspectorControls } = wp.blockEditor;
	const { PanelBody, ToggleControl } = wp.components;
	const { createElement: el, Fragment } = wp.element;

	/**
	 * 1. Add attribute to core/post-terms
	 */
	addFilter(
		'blocks.registerBlockType',
		'wpcn/post-terms/add-attribute',
		function (settings, name) {
			if (name !== 'core/post-terms') {
				return settings;
			}

			settings.attributes = Object.assign({}, settings.attributes, {
				displayCounts: {
					type: 'boolean',
					default: false
				},
				disableSingleLinks: {
					type: 'boolean',
					default: false
				}
			});

			return settings;
		}
	);

	/**
	 * 2. Add Inspector Control
	 */
	const withInspectorControls = createHigherOrderComponent(function (BlockEdit) {
		return function (props) {
			if (props.name !== 'core/post-terms') {
				return el(BlockEdit, props);
			}

			const { displayCounts, disableSingleLinks } = props.attributes;

			return el(
				Fragment,
				{},
				el(BlockEdit, props),
				props.isSelected &&
					el(
						InspectorControls,
						{},
						el(
							PanelBody,
							{ title: 'WPConstructor Terms Enhancer Settings' },
							el(ToggleControl, {
								label: 'Display term counts',
								checked: !!displayCounts,
								onChange(value) {
									props.setAttributes({
										displayCounts: value
									});
								}
							}),
							el(ToggleControl, {
								label: 'Prevent navigation to single-post term archives',
								checked: !!disableSingleLinks,
								onChange(value) {
									props.setAttributes({
										disableSingleLinks: value
									});
								}
							})
						)
					)
			);
		};
	}, 'withInspectorControls');

	addFilter(
		'editor.BlockEdit',
		'wpcn/post-terms/inspector',
		withInspectorControls
	);

	/**
	 * 3. Add editor class (visual only)
	 */
	addFilter(
		'editor.BlockListBlock',
		'wpcn/post-terms/class',
		function (BlockListBlockComponent) {
			return function (props) {
				if (props.name !== 'core/post-terms') {
					return el(BlockListBlockComponent, props);
				}

				const extraClass = props.attributes.displayCounts
					? 'wpcn-display-term-counts'
					: '';

				return el(BlockListBlockComponent, {
					...props,
					className: (props.className || '') + ' ' + extraClass
				});
			};
		}
	);
})(window.wp);
