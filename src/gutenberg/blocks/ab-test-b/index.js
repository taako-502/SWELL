/**
 * @WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import {
	InnerBlocks,
	useBlockProps,
	__experimentalUseInnerBlocksProps as useInnerBlocksProps,
} from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

const TEMPLATE = [['core/paragraph']];

/**
 * Bブロック
 */
const blockName = 'swell-block-abTest';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	edit: () => {
		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName}__b u-block-guide`,
		});

		const innerBlocksProps = useInnerBlocksProps(blockProps, {
			template: TEMPLATE,
			templateLock: false,
		});
		return <div {...innerBlocksProps} />;
	},
	save: () => {
		return <InnerBlocks.Content />;
	},
});
