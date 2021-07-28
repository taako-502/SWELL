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
 * 子要素 DD
 */
const getDdStyle = (dtWidth) => {
	const style = {};
	const offset = 2.5;
	if (dtWidth) {
		style.width = `calc(100% - ${dtWidth + offset}em)`;
	}
	return style;
};

/**
 * 項目の説明(DD)ブロック
 */
const blockName = 'swell-block-dl';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	getEditWrapperProps(attributes) {
		const { dtWidth } = attributes;
		const dtStyle = getDdStyle(dtWidth);
		return { style: dtStyle };
	},
	edit: () => {
		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName}__dd swl-inner-blocks swl-has-margin--s`,
		});

		const innerBlocksProps = useInnerBlocksProps(blockProps, {
			template: TEMPLATE,
		});

		return <div {...innerBlocksProps} />;
	},
	save: ({ attributes }) => {
		const ddStyle = getDdStyle(attributes.dtWidth);
		const blockProps = useBlockProps.save({
			className: `${blockName}__dd`,
			style: ddStyle || null,
		});

		return (
			<dd {...blockProps}>
				<InnerBlocks.Content />
			</dd>
		);
	},
});
