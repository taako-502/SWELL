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

const ALLOWED_BLOCKS = ['loos/column'];
const TEMPLATE = [['loos/column'], ['loos/column']];

/**
 * リッチカラム
 */
const blockName = 'swell-block-columns';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	styles: [
		{ name: 'default', label: 'デフォルト', isDefault: true },
		{ name: 'shadow', label: 'シャドウ' },
	],
	edit: (props) => {
		const { attributes, setAttributes } = props;

		// ブロックprops
		const blockProps = useBlockProps({
			className: blockName,
		});

		const innerBlocksProps = useInnerBlocksProps(
			{},
			{
				allowedBlocks: ALLOWED_BLOCKS,
				template: TEMPLATE,
				templateLock: false,
				orientation: 'horizontal',
				renderAppender: InnerBlocks.ButtonBlockAppender,
			}
		);

		return (
			<>
				{/* 左右marginの関係でカラムブロックは一つdivかませる */}
				<div {...blockProps}>
					<div {...innerBlocksProps} />
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		// ブロックprops
		const blockProps = useBlockProps.save({
			className: blockName,
		});

		return (
			<div {...blockProps}>
				<InnerBlocks.Content />
			</div>
		);
	},
});
