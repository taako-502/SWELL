/**
 * @WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
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

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * カラム項目
 */
const blockName = 'swell-block-columns';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	attributes: metadata.attributes,
	edit: (props) => {
		const { attributes, setAttributes, clientId } = props;

		// 子ブロックの設定
		const blockClass = classnames(
			`${blockName}__item`,
			'swl-inner-blocks',
			'swl-has-margin--s'
		);

		const hasChildBlocks = useSelect(
			(select) => {
				const { getBlockOrder } = select('core/block-editor');
				return getBlockOrder(clientId).length > 0;
			},
			[clientId]
		);

		// ブロックprops
		const blockProps = useBlockProps({
			className: blockClass,
		});
		const innerBlocksProps = useInnerBlocksProps(blockProps, {
			templateLock: false,
			renderAppender: hasChildBlocks ? undefined : InnerBlocks.ButtonBlockAppender,
		});

		return (
			<>
				<div {...innerBlocksProps} />
			</>
		);
	},
	save: ({ attributes }) => {
		// ブロックprops
		const blockProps = useBlockProps.save({
			className: `${blockName}__item`,
		});

		return (
			<div {...blockProps}>
				<InnerBlocks.Content />
			</div>
		);
	},
});
