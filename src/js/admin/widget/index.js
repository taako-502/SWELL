import domReady from '@wordpress/dom-ready';
import { render } from '@wordpress/element';
import { registerCoreBlocks } from '@wordpress/block-library';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { RichTextShortcut, RichTextToolbarButton } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import Editor from './editor';

/**
 * FSEブロックの停止
 */
import { addFilter } from '@wordpress/hooks';
addFilter('blocks.registerBlockType', 'swell/filter-fse-blocks', function (settings, name) {
	const fseBlocks = [
		'core/loginout',
		'core/page-list',
		'core/post-content',
		'core/post-date',
		'core/post-excerpt',
		'core/post-featured-image',
		'core/post-terms',
		'core/post-title',
		'core/post-template',
		'core/query-loop',
		'core/query',
		'core/query-pagination',
		'core/query-pagination-next',
		'core/query-pagination-numbers',
		'core/query-pagination-previous',
		'core/query-title',
		'core/site-logo',
		'core/site-title',
		'core/site-tagline',
	];
	if (-1 !== fseBlocks.indexOf(name)) {
		settings.supports.inserter = false;
	}
	return settings;
});

domReady(function () {
	registerBlockType('my-gutenblock/message', {
		title: 'message',
		category: 'common',
		icon: 'smiley',
	});

	registerFormatType('my-plugin/myspan', {
		title: 'My Span',
		tagName: 'span',
		className: 'my-span',
		edit({ isActive, value, onChange }) {
			const onToggle = () => onChange(toggleFormat(value, { type: 'my-plugin/myspan' }));

			return (
				<>
					<RichTextShortcut type='primary' character='m' onUse={onToggle} />
					<RichTextToolbarButton
						title='My Span'
						icon='megaphone'
						onClick={onToggle}
						isActive={isActive}
						shortcutType='primary'
						shortcutCharacter='m'
					/>
				</>
			);
		},
	});

	registerCoreBlocks();
	render(<Editor />, document.getElementById('swell_widget_editor'));
});
