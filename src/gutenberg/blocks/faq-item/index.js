/**
 * @WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { RichText, InnerBlocks } from '@wordpress/block-editor';

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
 * 子ブロック
 */
const blockName = 'swell-block-faq';
const qClass = 'faq_q';
const aClass = 'faq_a';
registerBlockType('loos/faq-item', {
	title: '項目',
	icon: {
		foreground: iconColor,
		src: blockIcon.faqChild,
	},
	category: 'swell-blocks',
	parent: ['loos/faq'],
	supports: {
		anchor: true,
		className: false,
		reusable: false,
	},
	attributes: {
		contentQ: {
			type: 'string',
			source: 'html',
			selector: `dt`,
			default: '',
		},
	},
	edit: ({ className, attributes, setAttributes }) => {
		const blockClass = classnames(className, `${blockName}__item`);

		// const allowedBlocks = ['core/paragraph', 'core/list', 'core/image'];
		return (
			<div className={blockClass}>
				<RichText
					className={qClass}
					tagName='div'
					placeholder={__('Text…', 'swell')}
					value={attributes.contentQ}
					onChange={(contentQ) => setAttributes({ contentQ })}
				/>
				<div className={aClass}>
					<InnerBlocks templateLock={false} template={[['core/paragraph']]} />
				</div>
			</div>
		);
	},
	save: ({ attributes }) => {
		const blockClass = `${blockName}__item`;
		return (
			<div className={blockClass}>
				<RichText.Content tagName='dt' className={qClass} value={attributes.contentQ} />
				<dd className={aClass}>
					<InnerBlocks.Content />
				</dd>
			</div>
		);
	},
});
