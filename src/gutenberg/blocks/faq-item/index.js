/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import {
	RichText,
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
 * 項目ブロック
 */
const blockName = 'swell-block-faq';
const qClass = 'faq_q';
const aClass = 'faq_a';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	parent: ['loos/faq'],
	edit: ({ attributes, setAttributes }) => {
		const { contentQ } = attributes;

		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName}__item swl-inner-blocks swl-has-margin--s`,
		});

		const innerBlocksProps = useInnerBlocksProps(
			{
				className: aClass,
			},
			{
				template: TEMPLATE,
				templateLock: false,
			}
		);

		return (
			<div {...blockProps}>
				<RichText
					className={qClass}
					tagName='div'
					placeholder={__('Text…', 'swell')}
					value={contentQ}
					onChange={(newContentQ) => setAttributes({ contentQ: newContentQ })}
				/>
				<div {...innerBlocksProps} />
			</div>
		);
	},
	save: ({ attributes }) => {
		const { contentQ } = attributes;

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: `${blockName}__item`,
		});

		return (
			<div {...blockProps}>
				<RichText.Content tagName='dt' className={qClass} value={contentQ} />
				<dd className={aClass}>
					<InnerBlocks.Content />
				</dd>
			</div>
		);
	},
});
