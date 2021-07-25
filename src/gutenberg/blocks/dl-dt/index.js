/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { RichText } from '@wordpress/block-editor';

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
 * 子要素 DT
 */
const getDtStyle = (dtWidth, isEdit) => {
	const style = {};
	const offset = isEdit ? 6 : 2.5;
	if (dtWidth) {
		style.width = dtWidth + offset + 'em';
	}
	return style;
};
registerBlockType('loos/dt', {
	title: '項目のタイトル(DT)',
	icon: {
		foreground: iconColor,
		src: blockIcon.dt,
	},
	category: 'swell-blocks',
	parent: ['loos/dl'],
	supports: {
		className: false,
		reusable: false,
	},
	attributes: {
		content: {
			type: 'string',
			source: 'html',
			selector: 'dt',
		},
		dtWidth: {
			type: 'number',
		},
	},
	getEditWrapperProps(attributes) {
		const { dtWidth } = attributes;
		const dtStyle = getDtStyle(dtWidth, true);
		return { style: dtStyle };
	},
	edit: ({ attributes, setAttributes, className }) => {
		const blockClass = classnames(className, blockName + '__dt');
		return (
			<RichText
				tagName='div'
				placeholder={__('Text…', 'swell')}
				value={attributes.content}
				className={blockClass}
				onChange={(content) => setAttributes({ content })}
			/>
		);
	},
	save: ({ attributes }) => {
		const dtStyle = getDtStyle(attributes.dtWidth, false);
		return (
			<RichText.Content
				tagName='dt'
				className={blockName + '__dt'}
				style={dtStyle || null}
				value={attributes.content}
			/>
		);
	},
});
