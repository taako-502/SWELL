/**
 * @WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * @Internal dependencies
 */
import metadata from './block.json';
import RssSidebar from './_sidebar';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * RSSブロック
 */
const blockName = 'swell-block-rss';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: ({ attributes, setAttributes }) => {
		// ブロックProps
		const blockProps = useBlockProps({
			className: classnames(blockName, 'post_content'),
		});

		return (
			<>
				<InspectorControls>
					<RssSidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...blockProps}>
					<ServerSideRender block='loos/rss' attributes={attributes} />
				</div>
			</>
		);
	},
	save: () => {
		return null;
	},
});
