/* eslint jsx-a11y/click-events-have-key-events: 0 */
/* eslint jsx-a11y/no-static-element-interactions: 0 */
/* eslint jsx-a11y/role-supports-aria-props: 0 */

/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import blockIcon from './_icon';
import metadata from './block.json';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * ブロッククラス名
 */
const blockName = 'swell-block-tab';

/**
 * 子ブロック
 */
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: () => {
		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName}__body c-tabBody__item swl-inner-blocks swl-has-margin--s`,
		});
		const innerBlocksProps = useInnerBlocksProps(blockProps, {
			template: [['core/paragraph']],
			templateLock: false,
		});

		return <div {...innerBlocksProps} />;
	},
	save: ({ attributes }) => {
		const { tabId, id, activeTab } = attributes;

		// ブロックprops
		const blockProps = useBlockProps.save({
			id: `tab-${tabId}-${id}`,
			className: 'c-tabBody__item',
			'aria-hidden': activeTab === id ? 'false' : 'true',
		});

		return (
			<div {...blockProps}>
				<InnerBlocks.Content />
			</div>
		);
	},
});
