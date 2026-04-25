/**
 *
 */

/* global jQuery */

'use strict';
const { select, dispatch, subscribe } = wp.data;

let loaded = false;
let waiting = true;
subscribe(() => {
	const isReady = select('core/editor').__unstableIsEditorReady();
	if (!isReady) {
		return;
	}
	const CSSExists = checkIfCSSLinkExists();
	if (!CSSExists && waiting === false) {
		loaded = false;
	}
	if (loaded === true) {
		return;
	}
	loaded = true;
	waiting = true;
	setTimeout(() => {
		addStyles();
		setTimeout(() => {
			waiting = false;
		}, 500);
	}, 2000);
});

const checkIfCSSLinkExists = function () {
	const iframe = jQuery('iframe[name="editor-canvas"]');
	const exists =
		iframe.contents().find('link[id="wpcn-display-term-counts-editor-styles"]')
			.length > 0;
	return exists;
};

//}
/* global WTCCssStyleSheet */

const changeClass = function (clientId, displayCounts) {
	const block = select('core/block-editor').getBlock(clientId);
	const attributes = { ...block.attributes };
	if (displayCounts) {
		if (attributes.className === undefined) {
			attributes.className = 'wpcn-display-term-counts';
		} else if (
			attributes.className.indexOf('wpcn-display-term-counts ') === -1 &&
			attributes.className !== 'wpcn-display-term-counts'
		) {
			attributes.className = 'wpcn-display-term-counts ' + attributes.className;
		}
	} else if (attributes.className !== undefined) {
		attributes.className = attributes.className.replace(
			'wpcn-display-term-counts ',
			''
		);
		if (attributes.className === 'wpcn-display-term-counts') {
			attributes.className = '';
		}
	}
	dispatch('core/block-editor').updateBlockAttributes(clientId, attributes);
};

const { createHigherOrderComponent } = wp.compose;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, CheckboxControl } = wp.components;
const { createElement } = wp.element;
const { addFilter } = wp.hooks;

const addStyles = function () {
	const iframe = jQuery('iframe[name="editor-canvas"]');
	const iframeHead = iframe.contents().find('head');
	let linkTag = jQuery('<link>');
	// @todo change random version to VERSION of php (Plugin Version).
	linkTag = jQuery(linkTag).attr({
		id: 'wpcn-display-term-counts-editor-styles',
		rel: 'stylesheet',
		type: 'text/css',
		href:
			WTCCssStyleSheet +
			'?ver=' +
			parseInt(Math.random() * 1000) +
			'-' +
			parseInt(Math.random() * 1000)
	});
	iframeHead.append(linkTag);
};

// Extend block settings
const withInspectorControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		if (props.name !== 'core/post-terms') {
			return createElement(BlockEdit, props);
		}

		const {
			attributes: { displayCounts },
			setAttributes,
			isSelected
		} = props;

		return createElement(
			'div',
			{},
			createElement(BlockEdit, props, 'test'),
			isSelected &&
				createElement(
					InspectorControls,
					{},
					createElement(
						PanelBody,
						{ title: 'WPConstructor Display Term Counts Settings' },
						createElement(CheckboxControl, {
							label: 'Display counts of terms in braces.',
							checked: displayCounts,
							onChange: (newVal) => {
								setAttributes({ displayCounts: newVal });
								changeClass(props.clientId, newVal);
							}
						})
					)
				)
		);
	};
}, 'withInspectorControls');

addFilter(
	'editor.BlockEdit',
	'wpcn-display-term-counts/add-settings-post-term-block',
	withInspectorControls
);

addFilter(
	'blocks.registerBlockType',
	'wpcn-display-term-counts/add-settings-post-term-block',
	(settings, name) => {
		if (name === 'core/post-terms') {
			settings.attributes = {
				...settings.attributes,
				displayCounts: {
					type: 'boolean',
					default: false
				}
			};
		}

		return settings;
	}
);

/*function addBlockClassName(extraProps, blockType, attributes) {
	if (blockType.name === 'core/post-terms') {
		// Add your custom class to the className property
		extraProps.className = (extraProps.className || '') + ' my-custom-class';
	}

	return extraProps;
}

wp.hooks.addFilter(
	'blocks.getSaveContent.extraProps',
	'my-plugin/add-block-class-name',
	addBlockClassName
);*/
