/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * @Internal dependencies
 */
import RssSidebar from './_sidebar';
import { iconColor } from '@swell-guten/config';
import example from './_example';
import metadata from './block.json';

/**
 * @Others dependencies
 */
// import classnames from 'classnames';

/**
 * metadata
 */
const blockName = 'swell-block-rss';
const { name, category, keywords, supports } = metadata;

/**
 * 設定
 */

registerBlockType(name, {
	title: __('RSS', 'swell'),
	description: __('Create a list of RSS feeds.', 'swell'),
	icon: {
		foreground: iconColor,
		src: 'rss',
	},
	category,
	keywords,
	example,
	supports,
	attributes: metadata.attributes,
	edit: ({ attributes, setAttributes, className }) => {
		return (
			<>
				<InspectorControls>
					<RssSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div className={`${blockName} ${className} post_content`}>
					<ServerSideRender block={name} attributes={attributes} />
				</div>
			</>
		);
	},
	save: () => {
		return null;
	},
});
