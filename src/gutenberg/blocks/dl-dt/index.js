/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { RichText, useBlockProps } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import metadata from './block.json';
import blockIcon from './_icon';
import getBlockIcon from '@swell-guten/utils/getBlockIcon';

/**
 * 子要素 DT
 */
const getDtStyle = (dtWidth) => {
	const style = {};
	const offset = 2.5;
	if (dtWidth) {
		style.width = dtWidth + offset + 'em';
	}
	return style;
};

/**
 * 項目のタイトル(DT)ブロック
 */
const blockName = 'swell-block-dl';
registerBlockType(metadata.name, {
	icon: getBlockIcon(blockIcon),
	getEditWrapperProps(attributes) {
		const { dtWidth } = attributes;
		const dtStyle = getDtStyle(dtWidth);
		return { style: dtStyle };
	},
	edit: ({ attributes, setAttributes }) => {
		const { content } = attributes;

		// ブロックprops
		const blockProps = useBlockProps({
			className: `${blockName}__dt`,
		});

		return (
			<RichText
				{...blockProps}
				tagName='div'
				placeholder={__('Text…', 'swell')}
				value={content}
				onChange={(newContent) => setAttributes({ content: newContent })}
			/>
		);
	},
	save: ({ attributes }) => {
		const { content } = attributes;
		const dtStyle = getDtStyle(attributes.dtWidth);

		// ブロックprops
		const blockProps = useBlockProps.save({
			className: `${blockName}__dt`,
			style: dtStyle || null,
		});

		return <RichText.Content {...blockProps} tagName='dt' value={content} />;
	},
});
