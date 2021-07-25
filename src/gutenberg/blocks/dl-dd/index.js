/**
 * @WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';

/**
 * @SWELL dependencies
 */
import blockIcon from './_icon';
import { iconColor } from '@swell-guten/config';

/**
 * @Others dependencies
 */
import classnames from 'classnames';

/**
 * registerBlockType
 */
const blockName = 'swell-block-dl';

/**
 * 子要素 DD
 */
const getDdStyle = (dtWidth, isEdit) => {
	const style = {};
	const offset = isEdit ? 6 : 2.5;
	if (dtWidth) {
		style.width = `calc(100% - ${dtWidth + offset}em)`;
	}
	return style;
};

registerBlockType('loos/dd', {
	title: '項目の説明(DD)',
	icon: {
		foreground: iconColor,
		src: blockIcon.dd,
	},
	category: 'swell-blocks',
	parent: ['loos/dl'],
	supports: {
		className: false,
		reusable: false,
	},
	attributes: {
		dtWidth: {
			type: 'number',
		},
	},
	getEditWrapperProps(attributes) {
		const { dtWidth } = attributes;
		const dtStyle = getDdStyle(dtWidth, true);
		return { style: dtStyle };
	},
	edit: ({ className }) => {
		const blockClass = classnames(className, blockName + '__dd');
		return (
			<div className={blockClass}>
				<InnerBlocks template={[['core/paragraph']]} />
			</div>
		);
	},
	save: ({ attributes }) => {
		const ddStyle = getDdStyle(attributes.dtWidth, false);
		return (
			<dd className={blockName + '__dd'} style={ddStyle || null}>
				<InnerBlocks.Content />
			</dd>
		);
	},
});
